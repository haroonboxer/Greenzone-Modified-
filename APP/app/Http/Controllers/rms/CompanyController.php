<?php

namespace App\Http\Controllers\rms;

use App\Http\Controllers\Controller;
use App\Http\Requests\rms\CompanyRequest;
use App\Http\Resources\rms\CompanyResource;
use App\Models\Auth\Attachments;
use App\Models\rms\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:company-list')->only('index');
        $this->middleware('permission:company-create')->only('store');
        $this->middleware('permission:company-view')->only('view');
        $this->middleware('permission:company-edit')->only('update');
        $this->middleware('permission:company-status')->only('changeStatus');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected array $sortFields = ['companies.id', 'companies.company_pa', 'companies.company_dr', 'companies.company_en', 'companies.created_at'];

    protected function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $query = Company::join('users', 'users.id', 'companies.created_by')
            ->select(
                'companies.id',
                'companies.company_pa',
                'companies.company_dr',
                'companies.company_en',
                'companies.icon',
                'companies.haq_alamatyaz',
                // 'companies.date_of_issue',
                // 'companies.date_of_validity',
                'companies.hanging_date',
                'companies.bank_account_number',
                'companies.amount_of_money',
                'companies.status',
                'companies.reason_dismissed',
                'companies.created_at',
                'users.name as ownerName',
            )
            ->orderBy($sortField, $sortOrder)
            ->when($request->company_dr != '', function ($query) use ($request) {
                return $query->where('companies.company_dr', 'LIKE', '%' . trim($request->company_dr) . '%');
            })
            ->when($request->company_en != '', function ($query) use ($request) {
                return $query->where('companies.company_en', 'LIKE', '%' . trim($request->company_en) . '%');
            });
        $perPage = $request->input('per_page') ?? self::PER_PAGE;
        $records = $query->paginate((int) $perPage);
        return CompanyResource::collection($records);
    }

    protected function store(CompanyRequest $request)
    {
        DB::beginTransaction();
        try {
            $iconPath = '';
            if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
                $iconPath = Storage::disk('attachments')->put(
                    'user/' . date('Y') . '/' . date('m'),
                    $request->file('icon')
                );
                $iconPath = asset('storage/' . $iconPath);
            }


            $company = new Company();
            $company->company_pa = $request->company_pa;
            $company->company_dr = $request->company_dr;
            $company->company_en = $request->company_en;
            $company->haq_alamatyaz = $request->haq_alamatyaz;
            // $company->date_of_issue = $request->date_of_issue;
            // $company->date_of_validity = $request->date_of_validity;
            $company->hanging_date = $request->hanging_date;
            $company->bank_account_number = $request->bank_account_number;
            $company->amount_of_money = $request->amount_of_money;
            $company->icon = $iconPath;
            $company->created_by = userid();
            $company->created_department = departmentId();
            $company->created_location = locationId();
            $company->created_at = now();
            $company->save();
            $parent_id = $company->id;

            if ($request->hasFile('attachments')) {
                $countAtt = count($request->file('attachments'));
                for ($i = 0; $i < $countAtt; $i++) {
                    if ($request->file('attachments')[$i]->isValid()) {
                        $path = $request->file('attachments')[$i]->store('companies/attachments/' . date('Y') . '/' . date('m'), 'public');
                        $attRecord = new Attachments();
                        $attRecord->parent_id = $parent_id;
                        $attRecord->file_name = $request->file('attachments')[$i]->getClientOriginalName();
                        $attRecord->file_size = $request->file('attachments')[$i]->getSize();
                        $attRecord->form_code = 'frm-05';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }
            DB::commit();
            return response()->json([
                'message' => 'Company created successfully.',
                'id' => $company->id
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response([
                'message' => 'Record not updated please check!',
            ], 500);
        }
    }

    protected function view($id = 0, Request $request)
    {
        if (!is_numeric($id)) {
            $id = decode_id($id);
        }

        $record = Company::join('users', 'users.id', '=', 'companies.created_by')
            ->join('provinces', 'provinces.id', '=', 'companies.created_location')
            ->join('departments', 'departments.id', '=', 'companies.created_department')
            ->select(
                'companies.id',
                'companies.company_pa',
                'companies.company_dr',
                'companies.company_en',
                'companies.icon',
                'companies.haq_alamatyaz',
                // 'companies.date_of_issue',
                // 'companies.date_of_validity',
                'companies.hanging_date',
                'companies.bank_account_number',
                'companies.amount_of_money',
                'companies.status',
                'companies.reason_dismissed',
                'companies.created_at',
                'users.name as createdBy',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment'
            )
            ->find($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found.'], 404);
        }

        $record->attachments = Attachments::where('parent_id', $id)
            ->where('form_code', 'frm-05')
            ->get(['id', 'file_name', 'file_size', 'path_name']);

        return response()->json([
            'record' => $record
        ], 200);
    }

    protected function update(CompanyRequest $request)
    {
        DB::beginTransaction();
        try {
            $company = Company::findOrFail($request->id);

            if ($request->hasFile('icon')) {
                $iconPath = Storage::disk('attachments')->put('user/' . date('Y') . '/' . date('m'), $request->file('icon'));
                $iconPath = asset('storage/' . $iconPath);
            } else {
                $iconPath = $company->icon;
            }
            $company->company_pa = $request->company_pa;
            $company->company_dr = $request->company_dr;
            $company->company_en = $request->company_en;
            $company->haq_alamatyaz = $request->haq_alamatyaz;
            // $company->date_of_issue = $request->date_of_issue;
            // $company->date_of_validity = $request->date_of_validity;
            $company->hanging_date = $request->hanging_date;
            $company->bank_account_number = $request->bank_account_number;
            $company->amount_of_money = $request->amount_of_money;
            $company->icon = $iconPath;
            $company->created_by = userid();
            $company->save();
            $parent_id = $company->id;

            if ($request->hasFile('attachments')) {
                $countAtt = count($request->file('attachments'));
                for ($i = 0; $i < $countAtt; $i++) {
                    if ($request->file('attachments')[$i]->isValid()) {
                        $path = $request->file('attachments')[$i]->store('companies/attachments/' . date('Y') . '/' . date('m'), 'public');
                        $attRecord = new Attachments();
                        $attRecord->parent_id = $parent_id;
                        $attRecord->file_name = $request->file('attachments')[$i]->getClientOriginalName();
                        $attRecord->file_size = $request->file('attachments')[$i]->getSize();
                        $attRecord->form_code = 'frm-05';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Company updated successfully.',
                'id' => $company->id
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Record not updated. Please check!',
            ], 500);
        }
    }

    protected function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:companies,id',
            'status' => 'required|boolean',
            'reason_dismissed' => 'required|string',
        ]);
        $company = Company::find($request->input('id'));
        if (!$company) {
            return response()->json([
                'message' => 'Company not found.',
            ], 404);
        }
        $parent_id = $company->id;

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('companies/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments();
                    $attRecord->parent_id = $parent_id;
                    $attRecord->file_name = $file->getClientOriginalName();
                    $attRecord->file_size = $file->getSize();
                    $attRecord->form_code = 'frm-05';
                    $attRecord->path_name = $path;
                    $attRecord->created_by = userid();
                    $attRecord->save();
                }
            }
        }

        $company->status = (int) $request->input('status');
        $company->reason_dismissed = $request->input('reason_dismissed');
        $company->save();

        return response()->json([
            'message' => 'Status updated successfully.',
        ], 200);
    }
}
