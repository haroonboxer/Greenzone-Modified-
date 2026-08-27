<?php

namespace App\Http\Controllers\green_zone;

use App\Http\Controllers\Controller;
use App\Http\Requests\green_zone\vehicleRequest;
use App\Http\Resources\green_zone\vehicleReource;
use App\Models\Auth\Attachments;
use App\Models\green_zone\vehicle;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;

class vehicleController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware('permission:vehicle-list')->only('index');
        $this->middleware('permission:vehicle-create')->only('store');
        $this->middleware('permission:vehicle-view')->only('view');
        $this->middleware('permission:vehicle-edit')->only('update');
        $this->middleware('permission:vehicle-status')->only('changeStatus');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected array $sortFields = [
        'vehicles.id',
        'vehicles.vehicle_type',
        'vehicles.vehicle_color',
        'vehicles.vehicle_platte_no',
        'vehicles.vehicle_engine_no',
        'vehicles.vehicle_source',
        'vehicles.status',
        'vehicles.created_at',
    ];

    protected function index(Request $request)
    {

        $user = Auth::guard('sso')->user();


        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);

        $query = Vehicle::with('type')

            ->leftJoin('drivers', 'drivers.vehicle_id', '=', 'vehicles.id')
            ->select(
                'vehicles.*',
                'vehicles.created_by_name as ownerName',
                'drivers.name as driverName'
            )
            ->orderBy($sortField, $sortOrder)
            ->when($request->vehicle_type != '', function ($query) use ($request) {
                return $query->where('vehicles.vehicle_type', 'LIKE', '%' . trim($request->vehicle_type) . '%');
            })
            ->when($request->vehicle_platte_no != '', function ($query) use ($request) {
                return $query->where('vehicles.vehicle_platte_no', 'LIKE', '%' . trim($request->vehicle_platte_no) . '%');
            })
            ->when($request->driverName != '', function ($query) use ($request) {
                return $query->where('drivers.name', 'LIKE', '%' . trim($request->driverName) . '%');
            })
            ->when($request->vehicle_engine_no != '', function ($query) use ($request) {
                return $query->where('vehicles.vehicle_engine_no', 'LIKE', '%' . trim($request->vehicle_engine_no) . '%');
            })
            ->when($request->vehicle_source != '', function ($query) use ($request) {
                return $query->where('vehicles.vehicle_source', 'LIKE', '%' . trim($request->vehicle_source) . '%');
            });

        $perPage = $request->input('per_page') ?? self::PER_PAGE;
        $records = $query->paginate((int) $perPage);

        return vehicleReource::collection($records);
    }

    protected function expiredLicenses(Request $request)
    {

        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $perPage = $request->input('per_page') ?? self::PER_PAGE;

        $query = Vehicle::with([
            'type',
            'licenses' => fn($q) => $q->where('status', 2), // only status = 2
            'creator'
        ])
            ->when(
                $request->vehicle_type != '',
                fn($q) =>
                $q->where('vehicle_type', 'LIKE', '%' . trim($request->vehicle_type) . '%')
            )
            ->when(
                $request->vehicle_platte_no != '',
                fn($q) =>
                $q->where('vehicle_platte_no', 'LIKE', '%' . trim($request->vehicle_platte_no) . '%')
            )
            ->when(
                $request->vehicle_engine_no != '',
                fn($q) =>
                $q->where('vehicle_engine_no', 'LIKE', '%' . trim($request->vehicle_engine_no) . '%')
            );

        $vehicles = $query->get();
        $today = Carbon::today();

        $expiredVehicles = $vehicles->filter(function ($vehicle) use ($today) {

            $licenses = $vehicle->licenses;

            if ($licenses->isEmpty()) {
                return false; // no status=2 license → exclude
            }

            $hasValidLicense = false;
            $hasExpiredLicense = false;

            foreach ($licenses as $license) {

                $val = trim($license->expire_date ?? '');
                if ($val === '') continue;

                if (!preg_match('/(\d{3,4})\D+(\d{1,2})\D+(\d{1,2})/', $val, $m)) {
                    continue;
                }

                try {
                    $expireCarbon = Jalalian::fromFormat(
                        'Y-m-d',
                        sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3])
                    )->toCarbon()->endOfDay();

                    if ($expireCarbon->gte($today)) {
                        $hasValidLicense = true;
                        break; // one valid license excludes vehicle
                    }

                    if ($expireCarbon->lt($today)) {
                        $hasExpiredLicense = true;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            return $hasExpiredLicense && !$hasValidLicense;
        });

        $expiredVehicles = $expiredVehicles->map(function ($vehicle) {
            $vehicle->ownerName = $vehicle->creator?->name ?? null;
            $vehicle->created_by = $vehicle->creator?->id ?? null;
            return $vehicle;
        });

        $page = $request->input('page', 1);
        $items = $expiredVehicles->forPage($page, $perPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $expiredVehicles->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return vehicleReource::collection($paginator);
    }


    public function store(vehicleRequest $request)
    {
        DB::beginTransaction();
        try {
            $frontPhotoPath = null;
            $backPhotoPath = null;
            $platePhotoPath = null;

            // Handle front photo
            if ($request->hasFile('front_photo')) {
                $frontPhotoStored = $request->file('front_photo')->store(
                    'vehicles/' . date('Y') . '/' . date('m'),
                    'public'
                );
                $frontPhotoPath = $frontPhotoStored ? asset('storage/' . $frontPhotoStored) : null;
            }

            // Handle back photo
            if ($request->hasFile('back_photo')) {
                $backPhotoStored = $request->file('back_photo')->store(
                    'vehicles/' . date('Y') . '/' . date('m'),
                    'public'
                );
                $backPhotoPath = $backPhotoStored ? asset('storage/' . $backPhotoStored) : null;
            }

            // Handle plate photo
            if ($request->hasFile('plate_photo')) {
                $platePhotoStored = $request->file('plate_photo')->store(
                    'vehicles/' . date('Y') . '/' . date('m'),
                    'public'
                );
                $platePhotoPath = $platePhotoStored ? asset('storage/' . $platePhotoStored) : null;
            }

            // Save vehicle
            $vehicle = new Vehicle();
            $vehicle->vehicle_type = $request->vehicle_type;
            $vehicle->vehicle_color = $request->vehicle_color;
            $vehicle->vehicle_platte_no = $request->vehicle_platte_no;
            $vehicle->vehicle_engine_no = $request->vehicle_engine_no;
            $vehicle->vehicle_source = $request->vehicle_source;
            $vehicle->front_photo = $frontPhotoPath;
            $vehicle->back_photo = $backPhotoPath;
            $vehicle->plate_photo = $platePhotoPath;
            $vehicle->created_by = userid();
            $vehicle->created_by_name = userName();
            $vehicle->created_department = departmentId();
            $vehicle->created_location = ProvinceId();
            $vehicle->created_at = now();
            $vehicle->save();

            // Save attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    if ($attachment->isValid()) {
                        $path = $attachment->store(
                            'vehicles/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments();
                        $attRecord->parent_id = $vehicle->id;
                        $attRecord->file_name = $attachment->getClientOriginalName();
                        $attRecord->file_size = $attachment->getSize();
                        $attRecord->form_code = 'frm-W1'; // Change if needed
                        $attRecord->path_name = asset('storage/' . $path);
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Vehicle created successfully.',
                'id' => $vehicle->id
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'message' => 'Vehicle creation failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    protected function view($id = 0)
    {

        if (!is_numeric($id)) {
            $id = decode_id($id);
        }

        // Change ->get() to ->first() to get a single model
        $record = vehicle::
            // join('users', 'users.id', '=', 'vehicles.created_by')
            //     ->join('provinces', 'provinces.id', '=', 'vehicles.created_location')
            //     ->join('departments', 'departments.id', '=', 'vehicles.created_department')
            select(
                'vehicles.*',
                'vehicles.created_by_name as ownerName',
                'vehicles.created_location as createdLocation',
                'vehicles.created_department as createdDepartment',
            )
            ->where('vehicles.id', $id)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Vehicle not found'], 404);
        }

        return new vehicleReource($record);
    }

    protected function update(Request $request)
    {

        DB::beginTransaction();
        try {
            $vehicle = Vehicle::findOrFail($request->id);

            // Update photos if provided
            if ($request->hasFile('front_photo')) {
                $frontPhotoPath = Storage::disk('attachments')->put(
                    'vehicles/' . date('Y') . '/' . date('m'),
                    $request->file('front_photo')
                );
                $vehicle->front_photo = 'storage/' . $frontPhotoPath;
            }

            if ($request->hasFile('back_photo')) {
                $backPhotoPath = Storage::disk('attachments')->put(
                    'vehicles/' . date('Y') . '/' . date('m'),
                    $request->file('back_photo')
                );
                $vehicle->back_photo = 'storage/' . $backPhotoPath;
            }

            if ($request->hasFile('plate_photo')) {
                $platePhotoPath = Storage::disk('attachments')->put(
                    'vehicles/' . date('Y') . '/' . date('m'),
                    $request->file('plate_photo')
                );
                $vehicle->plate_photo = 'storage/' . $platePhotoPath;
            }

            $vehicle->vehicle_type = $request->vehicle_type;
            $vehicle->vehicle_color = $request->vehicle_color;
            $vehicle->vehicle_platte_no = $request->vehicle_platte_no;
            $vehicle->vehicle_engine_no = $request->vehicle_engine_no;
            $vehicle->vehicle_source = $request->vehicle_source;
            $vehicle->updated_at = now();
            $vehicle->save();

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    if ($attachment->isValid()) {
                        $path = $attachment->store(
                            'vehicles/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments();
                        $attRecord->parent_id = $vehicle->id;
                        $attRecord->file_name = $attachment->getClientOriginalName();
                        $attRecord->file_size = $attachment->getSize();
                        $attRecord->form_code = 'frm-W1'; // keep consistent
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Vehicle updated successfully.',
                'id' => $vehicle->id
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Vehicle update failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
