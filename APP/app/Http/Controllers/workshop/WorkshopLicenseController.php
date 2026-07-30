<?php

namespace App\Http\Controllers\workshop;

use App\Http\Controllers\Controller;
use App\Http\Resources\workshop\WorkshopLicenseResource;
use App\Models\Auth\Attachments;
use App\Models\workshop\workshopBoss;
use App\Models\workshop\WorkshopLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class WorkshopLicenseController extends Controller
{
    protected $user;
    const DEFAULT_SORT_FIELD = 'workshop_licenses.id';
    const DEFAULT_SORT_ORDER = 'asc';
    const PER_PAGE = 10;

    public function __construct()
    {
        $this->middleware('permission:workshop-license-list')->only('index');
        $this->middleware('permission:workshop-license-create')->only('store');
        $this->middleware('permission:workshop-license-view')->only('view');
        $this->middleware('permission:workshop-license-edit')->only('update');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected array $sortFields = [
        'workshop_licenses.id',
        'workshop_licenses.issue_date',
        'workshop_licenses.validity_date',
        'workshop_licenses.fee',
    ];

    public function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->has('company_id') ? decode_id($request->company_id) : null;

        $query = WorkshopLicense::join('users', 'users.id', 'workshop_licenses.created_by')
            ->join('workshop_companies', 'workshop_companies.id', 'workshop_licenses.company_id')
            ->join('departments', 'departments.id', 'workshop_licenses.created_department')
            ->join('provinces', 'provinces.id', 'workshop_licenses.created_location')
            ->select(
                'workshop_licenses.*',
                'users.name as ownerName',
            )
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('workshop_licenses.company_id', $companyId);
            })
            ->when($request->filled('license_type'), function ($query) use ($request) {
                return $query->where('workshop_licenses.license_type', $request->license_type);
            })
            ->when($request->filled('slip_no'), function ($query) use ($request) {
                return $query->where('workshop_licenses.slip_no', 'LIKE', '%' . trim($request->slip_no) . '%');
            });

        $perPage = $request->input('per_page') ?? self::PER_PAGE;
        $records = $query->paginate((int) $perPage);

        return $records;
    }

    protected function store(Request $request)
    {
        $company_id = $request->company_id ? decode_id($request->company_id) : null;

        if (!$company_id) {
            return response([
                'message' => 'Invalid company ID.',
            ], 422);
        }

        $validated = $request->validate([
            'license_type' => 'required|in:new,extend,renew',
            'issue_date' => 'required',
            'validity_date' => 'required',
            'hanging_date' => 'required',
            'fee' => 'required|numeric',
            'bank_account_number' => 'required|numeric',
        ]);

        $record = new WorkshopLicense();
        $record->license_type = $validated['license_type'];
        $record->issue_date = $validated['issue_date'];
        $record->validity_date = $validated['validity_date'];
        $record->fee = $validated['fee'];
        $record->hanging_date = $validated['hanging_date'];
        $record->bank_account_number = $validated['bank_account_number'];
        $record->company_id = (int) $company_id;
        $record->created_by = userid();
        $record->created_department = departmentId();
        $record->created_location = locationId();
        $record->save();

        $parent_id = $record->id;

        // Generate serial number like AVWL-2025-000001
        $currentYear = Jalalian::now()->getYear();
        $serialNumber = 'AVWL-' . $currentYear . '-' . str_pad($parent_id, 6, '0', STR_PAD_LEFT);

        // Update the record with generated serial number
        $record->sn = $serialNumber;
        $record->save();

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment->isValid()) {
                    $path = $attachment->store('workshopLicenses/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments;
                    $attRecord->parent_id = $parent_id;
                    $attRecord->file_name = $attachment->getClientOriginalName();
                    $attRecord->file_size = $attachment->getSize();
                    $attRecord->form_code = 'frm-W4';
                    $attRecord->path_name = $path;
                    $attRecord->created_by = userid();
                    $attRecord->save();
                }
            }
        }

        return response([
            'message' => 'License successfully saved!',
            'id' => encode_id($parent_id),
        ], 200);
    }


    protected function view($id)
    {
        $license = WorkshopLicense::join('users', 'users.id', '=', 'workshop_licenses.created_by')
            ->join('provinces', 'provinces.id', '=', 'workshop_licenses.created_location')
            ->join('departments', 'departments.id', '=', 'workshop_licenses.created_department')
            ->select(
                'workshop_licenses.id',
                'workshop_licenses.license_type',
                'workshop_licenses.issue_date',
                'workshop_licenses.validity_date',
                'workshop_licenses.fee',
                'workshop_licenses.hanging_date',
                'workshop_licenses.bank_account_number',
                'workshop_licenses.status',
                'workshop_licenses.created_at',
                'users.name as ownerName',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
            )
            ->where('workshop_licenses.id', $id)
            ->firstOrFail();

        return new WorkshopLicenseResource($license);
    }

    protected function update(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $id = (int) $id;
            $license = WorkshopLicense::findOrFail($id);

            $license->update([
                'license_type' => $request->license_type,
                'issue_date' => $request->issue_date,
                'validity_date' => $request->validity_date,
                'fee' => $request->fee,
                'hanging_date' => $request->hanging_date,
                'bank_account_number' => $request->bank_account_number,
                'company_id' => $request->company_id ? (int) decode_id($request->company_id) : null,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store(
                            'licenses/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments;
                        $attRecord->parent_id = $id;
                        $attRecord->file_name = $file->getClientOriginalName();
                        $attRecord->file_size = $file->getSize();
                        $attRecord->form_code = 'frm-W4';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }
            DB::commit();
            return response([
                'message' => 'Record successfully updated!',
                'id' => encode_id($license->id),
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response([
                'message' => 'An error occurred while updating the record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:workshop_licenses,id',
            'status' => 'required',
        ]);

        $license = WorkshopLicense::find($request->input('id'));
        if (!$license) {
            return response()->json([
                'message' => 'License not found.',
            ], 404);
        }
        $license->status = (int) $request->input('status');
        $license->save();
        return response()->json([
            'message' => 'Status updated successfully.',
            'id' => encode_id($license->id),
        ], 200);
    }


    protected function changeStatusOfPrint(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:workshop_licenses,id',
            'printed' => 'required',
        ]);

        $license = WorkshopLicense::find($request->input('id'));
        if (!$license) {
            return response()->json([
                'message' => 'License not found.',
            ], 404);
        }
        $license->printed = (int) $request->input('printed');
        $license->save();
        return response()->json([
            'message' => 'printed status updated successfully.',
            'id' => encode_id($license->id),
        ], 200);
    }
}
