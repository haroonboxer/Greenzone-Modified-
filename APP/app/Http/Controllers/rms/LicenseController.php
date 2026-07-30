<?php

namespace App\Http\Controllers\rms;

use App\Http\Controllers\Controller;
use App\Http\Resources\rms\LicenseResource;
use App\Http\Resources\rms\LisenceResource;
use App\Models\rms\license;
use App\Models\Auth\Attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LicenseController extends Controller
{
    protected $user;
    const DEFAULT_SORT_FIELD = 'licenses.id';
    const DEFAULT_SORT_ORDER = 'asc';
    const PER_PAGE = 10;

    public function __construct()
    {
        $this->middleware('permission:license-list')->only('index');
        $this->middleware('permission:license-create')->only('store');
        $this->middleware('permission:license-view')->only('view');
        $this->middleware('permission:license-edit')->only('update');
        $this->middleware('permission:license-status')->only('changeStatus');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected array $sortFields = [
        'licenses.id',
        'licenses.issue_date',
        'licenses.validity_date',
        'licenses.license_date',
        'licenses.slip_no',
        'licenses.fee',
    ];

    public function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->has('company_id') ? decode_id($request->company_id) : null;

        $query = license::join('users', 'users.id', 'licenses.created_by')
            ->join('companies', 'companies.id', 'licenses.company_id')
            ->join('departments', 'departments.id', 'licenses.created_department')
            ->join('provinces', 'provinces.id', 'licenses.created_location')
            ->select(
                'licenses.id',
                'licenses.license_type',
                'licenses.issue_date',
                'licenses.validity_date',
                'licenses.license_date',
                'licenses.slip_no',
                'licenses.fee',
                'licenses.status',
                'licenses.created_at',
                'users.name as ownerName',
            )
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('licenses.company_id', $companyId);
            })
            ->when($request->filled('license_type'), function ($query) use ($request) {
                return $query->where('licenses.license_type', $request->license_type);
            })
            ->when($request->filled('slip_no'), function ($query) use ($request) {
                return $query->where('licenses.slip_no', 'LIKE', '%' . trim($request->slip_no) . '%');
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
            'license_date' => 'required',
            'slip_no' => 'required|string',
            'fee' => 'required|numeric',
        ]);

        $record = new License;
        $record->license_type = $validated['license_type'];
        $record->issue_date = $validated['issue_date'];
        $record->validity_date = $validated['validity_date'];
        $record->license_date = $validated['license_date'];
        $record->slip_no = $validated['slip_no'];
        $record->fee = $validated['fee'];
        $record->company_id = (int) $company_id;
        $record->created_by = userid();
        $record->created_by = userid();
        $record->created_department = departmentId();
        $record->created_location = locationId();
        $record->status = 1;
        $record->save();

        $parent_id = $record->id;

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment->isValid()) {
                    $path = $attachment->store('licenses/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments;
                    $attRecord->parent_id = $parent_id;
                    $attRecord->file_name = $attachment->getClientOriginalName();
                    $attRecord->file_size = $attachment->getSize();
                    $attRecord->form_code = 'frm-06';
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
        $license = License::join('users', 'users.id', '=', 'licenses.created_by')
            ->join('provinces', 'provinces.id', '=', 'licenses.created_location')
            ->join('departments', 'departments.id', '=', 'licenses.created_department')
            ->select(
                'licenses.id',
                'licenses.license_type',
                'licenses.issue_date',
                'licenses.validity_date',
                'licenses.license_date',
                'licenses.slip_no',
                'licenses.fee',
                'licenses.status',
                'licenses.created_at',
                'users.name as ownerName',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
            )
            ->where('licenses.id', $id)
            ->firstOrFail();

        return new LisenceResource($license);
    }

    protected function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = (int) $id;
            $license = License::findOrFail($id);

            $license->update([
                'license_type' => $request->license_type,
                'issue_date' => $request->issue_date,
                'validity_date' => $request->validity_date,
                'license_date' => $request->license_date,
                'slip_no' => $request->slip_no,
                'fee' => $request->fee,
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
                        $attRecord->form_code = 'frm-06';
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

    protected function createButton(Request $request)
    {
        $companyId = $request->has('id') ? decode_id($request->id) : null;
        $query = License::select('licenses.status')
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('licenses.company_id', $companyId)
                    ->where('licenses.status', 1);
            });
        $records = $query->get();
        return LicenseResource::collection($records);
    }

    protected function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:licenses,id',
            'status' => 'required',
            'reason_dismissed' => 'required|string'
        ]);

        $license = License::find($request->input('id'));
        if (!$license) {
            return response()->json([
                'message' => 'License not found.',
            ], 404);
        }
        $parent_id = $license->id;
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('licenses/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments();
                    $attRecord->parent_id = $parent_id;
                    $attRecord->file_name = $file->getClientOriginalName();
                    $attRecord->file_size = $file->getSize();
                    $attRecord->form_code = 'frm-06';
                    $attRecord->path_name = $path;
                    $attRecord->created_by = userid();
                    $attRecord->save();
                }
            }
        }

        $license->status = (int) $request->input('status');
        $license->reason_dismissed = $request->input('reason_dismissed');
        $license->save();
        return response()->json([
            'message' => 'Status updated successfully.',
            'id' => encode_id($license->id),
        ], 200);
    }
}
