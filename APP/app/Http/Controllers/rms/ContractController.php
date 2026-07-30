<?php

namespace App\Http\Controllers\rms;

use App\Http\Controllers\Controller;
use App\Http\Resources\rms\ContractResource;
use App\Models\Auth\Attachments;
use App\Models\rms\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware('permission:contract-list')->only('index');
        $this->middleware('permission:contract-create')->only('store');
        $this->middleware('permission:contract-view')->only('view');
        $this->middleware('permission:contract-edit')->only('update');
        $this->middleware('permission:contract-status')->only('changeStatus');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }
    protected array $sortFields = [
        'contracts.id',
        'contracts.contract_source',
        'contracts.contract_location',
        'contracts.equipments_value',
        'contracts.created_at',
    ];

    protected function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->has('company_id') ? decode_id($request->company_id) : null;

        $query = Contract::join('users', 'users.id', 'contracts.created_by')
            ->select(
                'contracts.id',
                'contracts.contract_source',
                'contracts.contract_location',
                'contracts.contract_start_date',
                'contracts.contract_end_date',
                'contracts.afghan_personal_count',
                'contracts.external_personal_count',
                'contracts.ammo_count',
                'contracts.vehical_count',
                'contracts.walkie_talkie_count',
                'contracts.equipments_value',
                'contracts.other_equipments',
                'contracts.status',
                'contracts.created_at',
                'users.name as ownerName'
            )
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('contracts.company_id', $companyId);
            })
            ->when($request->contract_source != '', function ($query) use ($request) {
                return $query->where('contracts.contract_source', 'LIKE', '%' . trim($request->contract_source) . '%');
            })
            ->when($request->contract_location != '', function ($query) use ($request) {
                return $query->where('contracts.contract_location', 'LIKE', '%' . trim($request->contract_location) . '%');
            })
            ->when($request->equipments_value != '', function ($query) use ($request) {
                return $query->where('contracts.equipments_value', 'LIKE', '%' . trim($request->equipments_value) . '%');
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
        $record = new Contract;
        $record->contract_source = $request->contract_source;
        $record->contract_location = $request->contract_location;
        $record->contract_start_date = $request->contract_start_date;
        $record->contract_end_date = $request->contract_end_date;
        $record->afghan_personal_count = $request->afghan_personal_count;
        $record->external_personal_count = $request->ext_personal_count;
        $record->ammo_count = $request->ammo_count;
        $record->vehical_count = $request->vehical_count;
        $record->walkie_talkie_count = $request->walkie_talkie_count;
        $record->equipments_value = $request->equipments_value;
        $record->other_equipments = $request->other_equipments;
        $record->company_id = (int) $company_id;
        $record->created_by = userid();
        $record->created_department = departmentId();
        $record->created_location = locationId();
        $record->save();

        $parent_id = $record->id;

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment->isValid()) {
                    $path = $attachment->store('contract/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments();
                    $attRecord->parent_id = $parent_id;
                    $attRecord->file_name = $attachment->getClientOriginalName();
                    $attRecord->file_size = $attachment->getSize();
                    $attRecord->form_code = 'frm-04';
                    $attRecord->path_name = $path;
                    $attRecord->created_by = userid();
                    $attRecord->save();
                }
            }
        }

        return response([
            'message' => 'Contract record successfully saved!',
            'id' => encode_id($parent_id),
        ], 200);
    }

    protected function view($id)
    {
        $contract = Contract::join('users', 'users.id', '=', 'contracts.created_by')
            ->join('provinces', 'provinces.id', '=', 'contracts.created_location')
            ->join('departments', 'departments.id', '=', 'contracts.created_department')
            ->select(
                'contracts.id',
                'contracts.contract_source',
                'contracts.contract_location',
                'contracts.contract_start_date',
                'contracts.contract_end_date',
                'contracts.afghan_personal_count',
                'contracts.external_personal_count',
                'contracts.ammo_count',
                'contracts.vehical_count',
                'contracts.walkie_talkie_count',
                'contracts.equipments_value',
                'contracts.other_equipments',
                'contracts.status',
                'contracts.created_at',
                'users.name as ownerName',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
            )
            ->where('contracts.id', $id)
            ->firstOrFail();

        return new ContractResource($contract);
    }

    protected function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = (int) $id;
            $contract = Contract::findOrFail($id);

            $contract->update([
                'contract_source' => $request->contract_source,
                'contract_location' => $request->contract_location,
                'contract_start_date' => $request->contract_start_date,
                'contract_end_date' => $request->contract_end_date,
                'afghan_personal_count' => $request->afghan_personal_count,
                'external_personal_count' => $request->ext_personal_count,
                'ammo_count' => $request->ammo_count,
                'vehical_count' => $request->vehical_count,
                'walkie_talkie_count' => $request->walkie_talkie_count,
                'equipments_value' => $request->equipments_value,
                'other_equipments' => $request->other_equipments,
                'company_id' => $request->company_id ? (int) decode_id($request->company_id) : null,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store(
                            'contract/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments;
                        $attRecord->parent_id = $id;
                        $attRecord->file_name = $file->getClientOriginalName();
                        $attRecord->file_size = $file->getSize();
                        $attRecord->form_code = 'frm-04';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();
            return response([
                'message' => 'Record successfully updated!',
                'id' => encode_id($contract->id),
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
            'id' => 'required|numeric|exists:contracts,id',
            'status' => 'required',
        ]);

        $contract = Contract::find($request->input('id'));
        if (!$contract) {
            return response()->json([
                'message' => 'Contract not found.',
            ], 404);
        }
        $contract->status = (int)$request->input('status');
        $contract->save();

        return response()->json([
            'message' => 'Status updated successfully.',
        ], 200);
    }

}
