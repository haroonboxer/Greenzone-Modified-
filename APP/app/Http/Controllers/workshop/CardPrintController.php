<?php

namespace App\Http\Controllers\workshop;

use App\Http\Controllers\Controller;
use App\Http\Resources\workshop\CardPrintResource;
use App\Models\workshop\WorkshopLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Milon\Barcode\DNS2D;


class CardPrintController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware('permission:workshop-print-list')->only('index');
        $this->middleware('permission:workshop-print-accept')->only('changeStatusOfLicense');
        $this->middleware('permission:workshop-print-card')->only('generateIDCard');
        $this->middleware('permission:workshop-print-card-view')->only('view');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    public array $sortFields = [
        'workshop_companies.company_dr',
        'workshop_licenses.license_type',
        'workshop_licenses.issued_date',
        'workshop_licenses.created_at',
    ];


    public function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->has('company_id') ? decode_id($request->company_id) : null;

        $query = DB::table('workshop_licenses')
            ->leftJoin('workshop_companies', 'workshop_licenses.company_id', '=', 'workshop_companies.id')
            ->leftJoin('workshop_bosses', 'workshop_bosses.company_id', '=', 'workshop_companies.id')
            ->leftJoin('workshop_assistants', 'workshop_assistants.company_id', '=', 'workshop_companies.id')
            ->leftJoin('users', 'workshop_licenses.created_by', '=', 'users.id')
            ->select(
                'workshop_licenses.*',
                'workshop_companies.company_dr as company_name_dr',
                'workshop_bosses.name_dr as boss_name_dr',
                'workshop_assistants.name_dr as assistant_name_dr',
                'users.name as ownerName'
            )
            ->whereIn('workshop_licenses.status', [1, 2, 3])
            ->whereIn('workshop_licenses.printed', [0])
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('workshop_licenses.company_id', $companyId);
            })
            ->when($request->input('license_type'), function ($query) use ($request) {
                return $query->where('workshop_licenses.license_type', $request->input('license_type'));
            });

        $perPage = $request->input('per_page') ?? self::PER_PAGE;
        $records = $query->paginate((int) $perPage);
        return CardPrintResource::collection($records);
    }

    public function changeStatusOfLicense(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:workshop_licenses,id',
            'status' => 'required',
            'reason' => [
                'required_if:status,4',
                'string',
                'max:255',
            ],
        ]);


        $printedLicense = WorkshopLicense::find($request->input('id'));

        if (!$printedLicense) {
            return response()->json([
                'message' => 'License not found.',
            ], 404);
        }

        $printedLicense->status = (int)$request->input('status');
        $printedLicense->reject_reason = $request->input('reason');
        $printedLicense->save();

        return response()->json([
            'message' => 'Printed card status updated successfully.',
        ], 200);
    }


    public function view($id)
    {
        $data = DB::table('workshop_licenses')
            ->join('workshop_companies', 'workshop_licenses.company_id', '=', 'workshop_companies.id')
            ->join('workshop_bosses', 'workshop_bosses.company_id', '=', 'workshop_companies.id')
            ->join('workshop_assistants', 'workshop_assistants.company_id', '=', 'workshop_companies.id')
            ->join('provinces', 'provinces.id', '=', 'workshop_licenses.created_location')
            ->join('departments', 'departments.id', '=', 'workshop_licenses.created_department')
            ->leftJoin('users', 'workshop_licenses.created_by', '=', 'users.id')
            ->select(
                'workshop_licenses.*',
                'workshop_licenses.license_type',
                'workshop_companies.company_dr as company_name_dr',
                'workshop_companies.company_en as company_name_en',
                'workshop_companies.icon as company_icon',
                'workshop_bosses.name_dr as boss_name_dr',
                'workshop_bosses.name_en as boss_name_en',
                'workshop_bosses.photo as boss_photo',
                'workshop_assistants.name_dr as assistant_name_dr',
                'workshop_assistants.name_en as assistant_name_en',
                'workshop_assistants.photo as assistant_photo',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
                'users.name as ownerName'
            )
            ->where('workshop_licenses.id', $id)
            ->first();

        if (!$data) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        return response()->json($data);
    }

    public function generateIDCard($id)
    {
        $data = DB::table('workshop_licenses')
            ->leftJoin('workshop_companies', 'workshop_licenses.company_id', '=', 'workshop_companies.id')
            ->leftJoin('workshop_bosses', 'workshop_bosses.company_id', '=', 'workshop_companies.id')
            ->leftJoin('workshop_assistants', 'workshop_assistants.company_id', '=', 'workshop_companies.id')
            ->leftJoin('users', 'workshop_licenses.created_by', '=', 'users.id')
            ->select(
                'workshop_licenses.*',
                'workshop_companies.company_dr as company_name_dr',
                'workshop_companies.company_en as company_name_en',
                'workshop_companies.icon as company_icon',
                'workshop_bosses.name_dr as boss_name_dr',
                'workshop_bosses.last_name_dr as boss_last_name_dr',
                'workshop_bosses.last_name_en as boss_last_name_en',
                'workshop_bosses.name_en as boss_name_en',
                'workshop_bosses.photo as boss_photo',
                'workshop_assistants.name_dr as assistant_name_dr',
                'workshop_assistants.last_name_dr as assistant_last_name_dr',
                'workshop_assistants.last_name_en as assistant_last_name_en',
                'workshop_assistants.name_en as assistant_name_en',
                'workshop_assistants.photo as assistant_photo',
                'users.name as ownerName'
            )
            ->where('workshop_licenses.id', $id)
            ->first();

        if (!$data) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // Use the actual serial number from the database
        $serialNumber = $data->sn ?? 'سریال نمبر موجود نمیباشد';

        $qrContent = $data->company_name_dr . "\n" . $data->boss_name_dr . "\n" . $serialNumber;

        $barcodeGenerator = new DNS2D();
        $barcodeGenerator->setStorPath(storage_path('framework/barcodes'));

        $encodedContent = mb_convert_encoding($qrContent, 'UTF-8', 'auto');
        $barcode = $barcodeGenerator->getBarcodePNG($encodedContent, 'QRCODE');
        $barcodeDataUri = 'data:image/png;base64,' . $barcode;

        $html = View::make('license', compact('data', 'barcodeDataUri', 'serialNumber'))->render();

        return response()->json([
            'html' => $html,
        ]);
    }
}
