<?php

namespace App\Http\Controllers\green_zone;

use App\Http\Controllers\Controller;
use App\Http\Resources\green_zone\CardResource;
use App\Models\green_zone\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Milon\Barcode\DNS2D;

class CardController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware('permission:cards')->only('index');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('sso')->user();
            return $next($request);
        });
    }

    public array $sortFields = [
        'gzlicenses.license_type',
        'drivers.name',
        'vehicles.plate_no',
        'vehicles.vehicle_type',
        'gzlicenses.created_at',
    ];


    // public function index(Request $request)
    // {


    //     $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
    //     $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
    //     $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
    //     $vehicleId = $request->has('vehicle_id') ? decode_id($request->vehicle_id) : null;

    //     $query = DB::table('gzlicenses')
    //         ->join('vehicles', 'vehicles.id', '=', 'gzlicenses.vehicle_id')
    //         ->leftJoin('vehicle_saves', 'vehicle_saves.id', '=', 'vehicles.vehicle_type')
    //         ->join('drivers', 'drivers.id', '=', 'gzlicenses.driver_id')
    //         // ->join('users', 'users.id', '=', 'gzlicenses.created_by')
    //         //->join('departments', 'departments.id', '=', 'gzlicenses.created_department')
    //         //->join('provinces', 'provinces.id', '=', 'gzlicenses.created_location')
    //         ->select(
    //             'gzlicenses.*',
    //             'vehicles.vehicle_type',
    //             'vehicle_saves.name as vehicle_type_name',
    //             'vehicles.vehicle_color',
    //             'vehicles.vehicle_source',
    //             'vehicles.vehicle_platte_no',
    //             'vehicles.front_photo as front_photo',
    //             'drivers.name as driver_name',
    //             'drivers.photo as driver_photo',
    //             'gzlicenses.created_by_name as created_by',
    //             // 'provinces.name_dr as createdLocation',
    //             // 'departments.name_da as createdDepartment',
    //         )
    //         ->whereIn('gzlicenses.status', [1])
    //         ->orderBy($sortField, $sortOrder)
    //         // filter by vehicle_id if provided
    //         ->when($vehicleId, function ($query) use ($vehicleId) {
    //             return $query->where('gzlicenses.vehicle_id', $vehicleId);
    //         })
    //         // filter by license_type if provided
    //         ->when($request->input('license_type'), function ($query) use ($request) {
    //             return $query->where('gzlicenses.license_type', $request->input('license_type'));
    //         })
    //         // search by vehicle name
    //         ->when($request->filled('vehicle_name'), function ($query) use ($request) {
    //             return $query->where('vehicles.vehicle_type', 'LIKE', '%' . trim($request->vehicle_name) . '%');
    //         })
    //         // search by driver name
    //         ->when($request->filled('driver_name'), function ($query) use ($request) {
    //             return $query->where('drivers.name', 'LIKE', '%' . trim($request->driver_name) . '%');
    //         })
    //         // search by plate number
    //         ->when($request->filled('plate_no'), function ($query) use ($request) {
    //             return $query->where('vehicles.vehicle_platte_no', 'LIKE', '%' . trim($request->plate_no) . '%');
    //         });

    //     $perPage = $request->input('per_page') ?? self::PER_PAGE;
    //     $records = $query->paginate((int) $perPage);

    //     return CardResource::collection($records);
    // }
    public function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);

        $sortField = in_array($sortFieldInput, $this->sortFields)
            ? $sortFieldInput
            : self::DEFAULT_SORT_FIELD;

        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);

        $vehicleId = $request->has('vehicle_id')
            ? decode_id($request->vehicle_id)
            : null;

        $query = DB::table('gzlicenses')
            ->join('vehicles', 'vehicles.id', '=', 'gzlicenses.vehicle_id')
            ->leftJoin('vehicle_saves', 'vehicle_saves.id', '=', 'vehicles.vehicle_type')
            ->join('drivers', 'drivers.id', '=', 'gzlicenses.driver_id')

            ->select(
                'gzlicenses.*',

                // Vehicle
                'vehicles.vehicle_type',
                'vehicles.vehicle_color',
                'vehicles.vehicle_source',
                'vehicles.vehicle_platte_no',
                'vehicles.front_photo',

                // Vehicle type
                'vehicle_saves.name as vehicle_type_name',

                // Driver
                'drivers.name as driver_name',
                'drivers.photo as driver_photo',

                // Created by name comes directly from vehicles
                'vehicles.created_by_name'
            )

            ->whereIn('gzlicenses.status', [1])

            ->orderBy($sortField, $sortOrder)

            ->when($vehicleId, function ($query) use ($vehicleId) {
                return $query->where('gzlicenses.vehicle_id', $vehicleId);
            })

            ->when($request->input('license_type'), function ($query) use ($request) {
                return $query->where(
                    'gzlicenses.license_type',
                    $request->input('license_type')
                );
            })

            ->when($request->filled('vehicle_name'), function ($query) use ($request) {
                return $query->where(
                    'vehicles.vehicle_type',
                    'LIKE',
                    '%' . trim($request->vehicle_name) . '%'
                );
            })

            ->when($request->filled('driver_name'), function ($query) use ($request) {
                return $query->where(
                    'drivers.name',
                    'LIKE',
                    '%' . trim($request->driver_name) . '%'
                );
            })

            ->when($request->filled('plate_no'), function ($query) use ($request) {
                return $query->where(
                    'vehicles.vehicle_platte_no',
                    'LIKE',
                    '%' . trim($request->plate_no) . '%'
                );
            });

        $perPage = $request->input('per_page') ?? self::PER_PAGE;

        $records = $query->paginate((int) $perPage);

        return CardResource::collection($records);
    }
    protected function changeStatusOfLicense(Request $request)
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
        $license->reject_reason = $request->input('reason');
        $license->save();
        return response()->json([
            'message' => 'status status updated successfully.',
            'id' => encode_id($license->id),
        ], 200);
    }

    protected function generateLicense(Request $request, $id = 0)
    {
        $data = License::join('vehicles', 'vehicles.id', '=', 'gzlicenses.vehicle_id')
            ->join('drivers', 'drivers.id', '=', 'gzlicenses.driver_id')
            ->join('users', 'users.id', '=', 'gzlicenses.created_by')
            ->leftJoin('provinces', 'provinces.id', '=', 'drivers.main_province')
            ->leftJoin('vehicle_saves', 'vehicle_saves.id', '=', 'vehicles.vehicle_type')
            ->where('gzlicenses.id', $id)
            ->select(
                'gzlicenses.*',
                'vehicles.*',
                'drivers.*',
                'drivers.name as driver_name',
                'drivers.photo as driver_photo',
                'provinces.name_dr as province_name',
                'vehicle_saves.name as vehicle_type'
            )
            ->first();

        if (!$data) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // ✅ QR Code Content (sn, driver name, vehicle type, expire date)
        $qrContent = "SN: {$data->sn}\n" .
            "Driver: {$data->driver_name}\n" .
            "Vehicle: {$data->vehicle_type}\n" .
            "Expire: {$data->expire_date}";

        $barcodeGenerator = new DNS2D();
        $barcodeGenerator->setStorPath(storage_path('framework/barcodes'));

        $encodedContent = mb_convert_encoding($qrContent, 'UTF-8', 'auto');
        $barcode = $barcodeGenerator->getBarcodePNG($encodedContent, 'QRCODE');
        $barcodeDataUri = 'data:image/png;base64,' . $barcode;

        // Render blade
        $html = View::make('license-print', compact('data', 'barcodeDataUri'))->render();

        return response()->json([
            'html' => $html,
        ]);
    }

    public function view(Request $request)
    {
        dd("Reached");
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);

        // --- Query without filters ---
        $query = License::join('users', 'users.id', '=', 'gzlicenses.created_by')
            ->join('vehicles', 'vehicles.id', '=', 'gzlicenses.vehicle_id')
            ->join('drivers', 'drivers.id', '=', 'gzlicenses.driver_id')
            ->join('departments', 'departments.id', '=', 'gzlicenses.created_department')
            ->join('provinces', 'provinces.id', '=', 'gzlicenses.created_location')
            ->select(
                'gzlicenses.*',
                'vehicles.vehicle_type',
                'vehicles.vehicle_color',
                'vehicles.vehicle_platte_no',
                'vehicles.vehicle_engine_no',
                'vehicles.vehicle_source',
                'vehicles.front_photo as vehicle_front_photo',
                'vehicles.back_photo as vehicle_back_photo',
                'vehicles.plate_photo as vehicle_plate_photo',
                'vehicles.status as vehicle_status',
                'drivers.name as driver_name',
                'drivers.f_name as driver_f_name',
                'drivers.g_f_name as driver_g_f_name',
                'drivers.nic as driver_nic',
                'drivers.phone as driver_phone',
                'drivers.photo as driver_photo',
                'drivers.main_province',
                'drivers.main_district',
                'drivers.main_village',
                'drivers.current_province',
                'drivers.current_district',
                'drivers.current_village',
                'drivers.status as driver_status',
                'drivers.reason_dismissed',
                'users.name as owner_name',
                'departments.name as department_name',
                'provinces.name as province_name'
            )
            ->orderBy($sortField, $sortOrder);

        // --- Pagination ---
        $perPage = (int) $request->input('per_page', 10);
        $records = $query->paginate($perPage);

        return $records;
    }
}
