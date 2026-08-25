<?php

namespace App\Http\Controllers\green_zone;

use App\Http\Controllers\Controller;
use App\Models\Auth\Attachments;
use App\Models\green_zone\driver;
use App\Models\green_zone\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class LicenseController extends Controller
{

    protected $user;
    const DEFAULT_SORT_FIELD = 'gzlicenses.id';
    const DEFAULT_SORT_ORDER = 'asc';
    const PER_PAGE = 10;

    public function __construct()
    {
        $this->middleware('permission:license-list')->only('index');
        $this->middleware('permission:license-create')->only('store');
        $this->middleware('permission:license-view')->only('view');
        $this->middleware('permission:license-edit')->only('update');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected array $sortFields = [
        'gzlicenses.id',
        'gzlicenses.license_type',
        'gzlicenses.sn',
    ];

    public function index(Request $request)
    {

        $sortFieldInput = $request->input('sort_field', 'id');
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : 'id';
        $sortOrder = $request->input('sort_order', 'desc');
        $vehicleId = $request->input('vehicle_id');

        if ($vehicleId && !is_numeric($vehicleId)) {
            $decodedVehicle = decode_id($vehicleId);
            $vehicleId = is_numeric($decodedVehicle) ? $decodedVehicle : ($decodedVehicle['id'] ?? null);
        }
        // in here
        // join('users', 'users.id', 'gzlicenses.created_by')
        // 
        $query = License::join('vehicles', 'vehicles.id', 'gzlicenses.vehicle_id')
            ->join('drivers', 'drivers.id', 'gzlicenses.driver_id')
            ->select(
                'gzlicenses.*',
                'drivers.name as driver_name',
                'gzlicenses.created_by_name as ownerName'
            )
            ->when($vehicleId, fn($q) => $q->where('gzlicenses.vehicle_id', $vehicleId))
            ->when($request->filled('license_type'), fn($q) => $q->where('gzlicenses.license_type', $request->license_type))
            ->when($request->filled('sn'), fn($q) => $q->where('gzlicenses.sn', 'LIKE', '%' . trim($request->sn) . '%'))
            ->when($request->filled('driver_name'), fn($q) => $q->where('drivers.name', 'LIKE', '%' . trim($request->driver_name) . '%'))
            ->orderBy($sortField, $sortOrder);

        $perPage = (int) $request->input('per_page', 10);
        $records = $query->paginate($perPage);

        return $records;
    }

    protected function store(Request $request)
    {
        try {
            $vehicle_id = $request->vehicle_id ? (int) decode_id($request->vehicle_id) : null;

            $validated = $request->validate([
                'license_type' => 'required|in:new,extend,renew',
                'issue_date'   => 'required',
                'expire_date'  => 'required|after:issue_date',
            ]);

            $driver = driver::where('vehicle_id', $vehicle_id)
                ->where('status', 1)
                ->first();

            $record = new license();
            $record->license_type       = $validated['license_type'];
            $record->issue_date         = $validated['issue_date'];
            $record->expire_date        = $validated['expire_date'];
            $record->vehicle_id         = $vehicle_id;
            $record->driver_id          = $driver ? $driver->id : null;
            $record->created_by         = userid();
            $record->created_by_name    = userName();
            $record->created_department = departmentId();
            $record->created_location   = ProvinceId();
            $record->status             = 0;
            $record->printed            = 0;

            $record->save();

            $parent_id = $record->id;

            // serial number
            $currentYear   = Jalalian::now()->getYear();
            $serialNumber  = 'GZL-' . $currentYear . '-' . str_pad($parent_id, 6, '0', STR_PAD_LEFT);

            $record->sn = $serialNumber;
            $record->save();

            // attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    if ($attachment->isValid()) {
                        $path = $attachment->store(
                            'gzlicenses/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments();
                        $attRecord->parent_id  = $parent_id;
                        $attRecord->file_name  = $attachment->getClientOriginalName();
                        $attRecord->file_size  = $attachment->getSize();
                        $attRecord->form_code  = 'frm-GZL';
                        $attRecord->path_name  = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            return response([
                'message' => 'License successfully saved!',
                'id'      => encode_id($parent_id),
            ], 200);
        } catch (\Exception $e) {
            $errorKey = 'license.save_error'; // default key

            if (str_contains($e->getMessage(), 'driver_id')) {
                $errorKey = 'gzlicense.driver_required';
            }

            return response([
                'error' => 'License save failed',
                'key'   => $errorKey, // important for frontend
            ], 500);
        }
    }

    protected function view($id = 0)
    {
        if (!is_numeric($id)) {
            $id = decode_id($id);
        }

        $record = License::join('vehicles', 'vehicles.id', '=', 'gzlicenses.vehicle_id')
            ->join('drivers', 'drivers.id', '=', 'gzlicenses.driver_id')
            ->join('users', 'users.id', '=', 'gzlicenses.created_by')
            ->join('departments', 'departments.id', '=', 'gzlicenses.created_department')
            ->join('provinces as created_prov', 'created_prov.id', '=', 'gzlicenses.created_location')
            ->leftJoin('vehicle_saves', 'vehicle_saves.id', '=', 'vehicles.vehicle_type')

            // Join driver’s address tables
            ->leftJoin('provinces as main_prov', 'main_prov.id', '=', 'drivers.main_province')
            ->leftJoin('districts as main_dist', 'main_dist.id', '=', 'drivers.main_district')
            ->leftJoin('provinces as current_prov', 'current_prov.id', '=', 'drivers.current_province')
            ->leftJoin('districts as current_dist', 'current_dist.id', '=', 'drivers.current_district')

            ->select(
                // License info
                'gzlicenses.*',
                'gzlicenses.driver_id',

                // Vehicle info
                'vehicles.vehicle_type',
                'vehicle_saves.name as vehicle_type_name',
                'vehicles.vehicle_color',
                'vehicles.vehicle_platte_no',
                'vehicles.front_photo as front_photo',
                'vehicles.back_photo as back_photo',

                // Driver info
                'drivers.name as driver_name',
                'drivers.f_name as driver_f_name',
                'drivers.g_f_name as driver_g_f_name',
                'drivers.photo as driver_photo',
                'drivers.nic as driver_nic',
                'drivers.phone as driver_phone',
                'drivers.main_village',
                'drivers.current_village',

                // Joined province & district names
                'main_prov.name_dr as main_province_name',
                'main_dist.district_dr as main_district_name',
                'current_prov.name_dr as current_province_name',
                'current_dist.district_dr as current_district_name',

                // Record creator info
                'users.name as created_by',
                'created_prov.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
            )
            ->where('gzlicenses.id', $id)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'License not found.',
            ], 404);
        }

        return response()->json($record, 200);
    }

    public function update(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = decode_id($id);
        }

        $license = License::find($id);

        if (!$license) {
            return response()->json([
                'message' => 'License not found.'
            ], 404);
        }

        try {
            // Validate main fields
            $validated = $request->validate([
                'license_type' => 'sometimes|in:new,extend,renew',
                'issue_date'   => 'sometimes|date',
                'expire_date'  => 'sometimes|date|after:issue_date',
            ]);

            // Update basic info
            $license->fill($validated);
            $license->save();

            // ✅ Handle attachments (like in store)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    if ($attachment->isValid()) {
                        $path = $attachment->store(
                            'gzlicenses/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments();
                        $attRecord->parent_id  = $license->id;
                        $attRecord->file_name  = $attachment->getClientOriginalName();
                        $attRecord->file_size  = $attachment->getSize();
                        $attRecord->form_code  = 'frm-GZL';
                        $attRecord->path_name  = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            return response()->json([
                'message' => 'License updated successfully.',
                'data'    => $license
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'License update failed',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    protected function sentPrint(Request $request)
    {

        $request->validate([
            'id' => 'required|numeric|exists:gzlicenses,id',
            'status' => 'required',
        ]);

        $license = license::find($request->input('id'));
        if (!$license) {
            return response()->json([
                'message' => 'License not found.',
            ], 404);
        }
        $license->status = (int) $request->input('status');
        $license->save();
        return response()->json([
            'message' => 'printed status updated successfully.',
            'id' => encode_id($license->id),
        ], 200);
    }
}
