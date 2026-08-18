<?php

namespace App\Http\Controllers\green_zone;

use App\Http\Controllers\Controller;
use App\Http\Requests\green_zone\driverRequest;
use App\Http\Resources\green_zone\driverResource;
use App\Models\Auth\Attachments;
use App\Models\green_zone\driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class driverController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware('permission:driver-list')->only('index');
        $this->middleware('permission:driver-create')->only('store');
        $this->middleware('permission:driver-view')->only('view');
        $this->middleware('permission:driver-status')->only('changeStatus');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }
    protected array $sortFields = [
        'drivers.id',
        'drivers.name',
        'drivers.f_name',
        'drivers.status',
        'drivers.created_at'
    ];

    // protected function index(Request $request)
    // {
    //     $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
    //     $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
    //     $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);

    //     $vehicleId = $request->has('vehicle_id') ? decode_id($request->vehicle_id) : null;
    //     $name = $request->input('name') ?? '';
    //     $fatherName = $request->input('f_name') ?? '';
    //     $phone = $request->input('phone') ?? '';

    //     $query = driver::join('users', 'users.id', 'drivers.created_by')
    //         ->select(
    //             'drivers.*',
    //             'drivers.photo',
    //             'users.name as ownerName'
    //         )
    //         ->orderBy($sortField, $sortOrder)
    //         ->when($vehicleId, fn($q) => $q->where('drivers.vehicle_id', $vehicleId))
    //         ->when($name !== '', fn($q) => $q->where('drivers.name', 'LIKE', "%$name%"))
    //         ->when($fatherName !== '', fn($q) => $q->where('drivers.f_name', 'LIKE', "%$fatherName%"))
    //         ->when($phone !== '', fn($q) => $q->where('drivers.phone', 'LIKE', "%$phone%"));

    //     $perPage = (int) $request->input('per_page', self::PER_PAGE);
    //     $records = $query->paginate($perPage);

    //     return driverResource::collection($records);
    // }

    protected function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);

        $vehicleId = $request->has('vehicle_id') ? decode_id($request->vehicle_id) : null;
        $name = $request->input('name') ?? '';
        $fatherName = $request->input('f_name') ?? '';
        $phone = $request->input('phone') ?? '';

        $query = driver::join('users', 'users.id', 'drivers.created_by')
            ->select(
                'drivers.*',
                'drivers.photo',
                'users.name as ownerName'
            )
            ->orderBy($sortField, $sortOrder)
            ->when($vehicleId, fn($q) => $q->where('drivers.vehicle_id', $vehicleId))
            ->when($name !== '', fn($q) => $q->where('drivers.name', 'LIKE', "%$name%"))
            ->when($fatherName !== '', fn($q) => $q->where('drivers.f_name', 'LIKE', "%$fatherName%"))
            ->when($phone !== '', fn($q) => $q->where('drivers.phone', 'LIKE', "%$phone%"));

        $perPage = (int) $request->input('per_page', self::PER_PAGE);
        $records = $query->paginate($perPage);

        // ✅ Determine if the vehicle has any active driver (status = 1)
        $hasActiveDriver = false;
        if ($vehicleId) {
            $hasActiveDriver = driver::where('vehicle_id', $vehicleId)
                ->where('status', 1)
                ->exists();
        }

        // ✅ Attach metadata to resource collection
        return driverResource::collection($records)->additional([
            'meta' => [
                'has_active_driver' => $hasActiveDriver,
            ],
        ]);
    }



    protected function store(driverRequest $request)
    {
      


        DB::beginTransaction();
        try {
            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store(
                    'drivers/photos/' . date('Y') . '/' . date('m'),
                    'public'
                );
            }

            $driver = new Driver();
            $driver->name             = $request->name;
            $driver->f_name           = $request->f_name;
            $driver->g_f_name         = $request->g_f_name;
            $driver->nic              = $request->nic;
            $driver->phone            = $request->phone;
            $driver->photo            = $photoPath ? asset('storage/' . $photoPath) : null;

            // Optional addresses
            $driver->main_province    = $request->main_province;
            $driver->main_district    = $request->main_district;
            $driver->main_village     = $request->main_village;
            $driver->current_province = $request->current_province;
            $driver->current_district = $request->current_district;
            $driver->current_village  = $request->current_village;

            // Defaults
            $driver->status           = $request->status ?? 1;
            $driver->reason_dismissed = $request->reason_dismissed ?? null;

            // Required foreign keys
            $driver->vehicle_id         = (int) decode_id($request->vehicle_id);
            $driver->created_by         = userid();
            $driver->created_department = departmentId();
            $driver->created_location   = locationId();

            $driver->save();

            // Handle attachments if provided
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store(
                            'drivers/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments();
                        $attRecord->parent_id  = $driver->id;
                        $attRecord->file_name  = $file->getClientOriginalName();
                        $attRecord->file_size  = $file->getSize();
                        $attRecord->form_code  = 'frm-DRV';
                        $attRecord->path_name  = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();

            return response([
                'message' => 'Driver record successfully saved!',
                'id'      => encode_id($driver->id),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    protected function view($id = 0, Request $request)
    {
        if (!is_numeric($id)) {
            $id = decode_id($id);
        }

        $record = driver::join('users', 'users.id', '=', 'drivers.created_by')
            ->join('provinces', 'provinces.id', '=', 'drivers.created_location')
            ->join('departments', 'departments.id', '=', 'drivers.created_department')
            ->leftJoin('provinces as main_province', 'main_province.id', '=', 'drivers.main_province')
            ->leftJoin('districts as main_district', 'main_district.id', '=', 'drivers.main_district')
            ->leftJoin('provinces as current_province', 'current_province.id', '=', 'drivers.current_province')
            ->leftJoin('districts as current_district', 'current_district.id', '=', 'drivers.current_district')
            ->leftJoin('vehicles', 'vehicles.id', '=', 'drivers.vehicle_id')
            ->leftJoin('vehicle_saves', 'vehicle_saves.id', '=', 'vehicles.vehicle_type') // 👈 add this
            ->select(
                'drivers.*',
                'users.name as createdBy',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
                'main_province.name_dr as mainProvince',
                'main_district.district_dr as mainDistrict',
                'current_province.name_dr as currentProvince',
                'current_district.district_dr as currentDistrict',
                'vehicles.vehicle_type as vehicle_type_id',   // keep raw id if you need
                'vehicle_saves.name as vehicle_type_name'     // 👈 human-readable name
            )
            ->find($id);

        return response()->json([
            'record' => $record,
        ], 200);
    }



    protected function update(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = decode_id($id);
        }

        DB::beginTransaction();
        try {
            $driver = Driver::findOrFail($id);

            // Handle photo upload (replace if new photo given)
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store(
                    'drivers/photos/' . date('Y') . '/' . date('m'),
                    'public'
                );
                $driver->photo = asset('storage/' . $photoPath);
            }

            // Update basic info
            $driver->name             = $request->name;
            $driver->f_name           = $request->f_name;
            $driver->g_f_name         = $request->g_f_name;
            $driver->nic              = $request->nic;
            $driver->phone            = $request->phone;

            // Update addresses
            $driver->main_province    = $request->main_province;
            $driver->main_district    = $request->main_district;
            $driver->main_village     = $request->main_village;
            $driver->current_province = $request->current_province;
            $driver->current_district = $request->current_district;
            $driver->current_village  = $request->current_village;

            // Update status fields
            $driver->status           = $request->status ?? $driver->status;
            $driver->reason_dismissed = $request->reason_dismissed ?? $driver->reason_dismissed;

            // Vehicle (foreign key)
            if ($request->vehicle_id) {
                $driver->vehicle_id = (int) decode_id($request->vehicle_id);
            }

            $driver->save();

            // Handle new attachments (if any)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store(
                            'drivers/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments();
                        $attRecord->parent_id  = $driver->id;
                        $attRecord->file_name  = $file->getClientOriginalName();
                        $attRecord->file_size  = $file->getSize();
                        $attRecord->form_code  = 'frm-DRV';
                        $attRecord->path_name  = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();

            return response([
                'message' => 'Driver record successfully updated!',
                'id'      => encode_id($driver->id),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    protected function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:drivers,id',
            'status' => 'required|boolean',
        ]);

        $driver = driver::find($request->input('id'));
        if (!$driver) {
            return response()->json([
                'message' => 'Driver not found.',
            ], 404);
        }

        $newStatus = (int) $request->input('status');

        // If trying to activate this driver (status = 1)
        if ($newStatus === 1) {
            $activeExists = driver::where('vehicle_id', $driver->vehicle_id)
                ->where('status', 1)
                ->where('id', '!=', $driver->id) // exclude current driver
                ->exists();

            if ($activeExists) {
                return response()->json([
                    'error_code' => 'active_driver_exists', // generic error key
                ], 422);
            }
        }

        $driver->status = $newStatus;
        $driver->reason_dismissed = $request->input('reason_dismissed');
        $driver->save();

        return response()->json([
            'message' => 'Status updated successfully.',
        ], 200);
    }
}
