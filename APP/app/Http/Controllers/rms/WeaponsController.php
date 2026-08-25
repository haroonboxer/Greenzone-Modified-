<?php

namespace App\Http\Controllers\rms;

use App\Http\Controllers\Controller;
use App\Http\Requests\rms\WeaponRequest;
use App\Http\Resources\rms\WeaponResource;
use App\Models\Auth\Attachments;
use App\Models\rms\WeaponsGeneralTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class WeaponsController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware('permission:weapon-list')->only('index');
        $this->middleware('permission:weapon-create')->only('store');
        $this->middleware('permission:weapon-view')->only('view');
        $this->middleware('permission:weapon-edit')->only('update');
        $this->middleware('permission:weapon-status')->only('changeStatus');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected array $sortFields = [
        'weapons_general_tables.id',
        'weapons_general_tables.number_of_weapons',
        'weapons_general_tables.slip_no',
        'weapons_general_tables.money_amount',
        'weapons_general_tables.slip_date',
        'weapons_general_tables.status'
    ];

    protected function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->filled('company_id') ? decode_id($request->input('company_id')) : null;

        $query = WeaponsGeneralTable::join('users', 'users.id', '=', 'weapons_general_tables.created_by')
            ->select(
                'weapons_general_tables.id',
                'weapons_general_tables.number_of_weapons',
                'weapons_general_tables.slip_no',
                'weapons_general_tables.money_amount',
                'weapons_general_tables.slip_date',
                'weapons_general_tables.company_id',
                'weapons_general_tables.status',
                'weapons_general_tables.reason_dismissed',
                'weapons_general_tables.created_at',
                'users.name as ownerName',
            )
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('weapons_general_tables.company_id', $companyId);
            })
            ->when($request->filled('number_of_weapons'), function ($query) use ($request) {
                return $query->where('weapons_general_tables.number_of_weapons', 'LIKE', '%' . trim($request->input('number_of_weapons')) . '%');
            });
        $perPage = (int) $request->input('per_page', self::PER_PAGE);
        $records = $query->paginate($perPage);
        return response()->json([
            'data' => $records->items(),
            'meta' => [
                'total' => $records->total(),
                'per_page' => $records->perPage(),
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'from' => $records->firstItem(),
                'to' => $records->lastItem(),
            ]
        ]);
    }

    protected function store(WeaponRequest $request)
    {
        DB::beginTransaction();
        try {
            $company_id = $request->company_id ? (int) decode_id($request->company_id) : null;

            $record = WeaponsGeneralTable::create([
                'number_of_weapons' => $request->number_of_weapons,
                'slip_no' => $request->slip_no,
                'money_amount' => $request->money_amount,
                'slip_date' => $request->slip_date,
                'company_id' => $company_id,
                'created_by' => userid(),
                'created_by_name' => userName(),
                'created_department' => departmentId(),
                'created_location' => ProvinceId(),
            ]);

            $parent_id = $record->id;

            if ($request->hasFile('attachments')) {
                $countAtt = count($request->file('attachments'));
                for ($i = 0; $i < $countAtt; $i++) {
                    if ($request->file('attachments')[$i]->isValid()) {
                        $path = $request->file('attachments')[$i]->store('weapons/attachments/' . date('Y') . '/' . date('m'), 'public');
                        $attRecord = new Attachments();
                        $attRecord->parent_id = $parent_id;
                        $attRecord->file_name = $request->file('attachments')[$i]->getClientOriginalName();
                        $attRecord->file_size = $request->file('attachments')[$i]->getSize();
                        $attRecord->form_code = 'frm-7';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();
            return response([
                'message' => 'Weapon record successfully saved!',
                'id' => encode_id($record->id),
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response(['message' => 'An error occurred while saving the weapon record.'], 500);
        }
    }

    protected function update(WeaponRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = (int) $id;
            $record = WeaponsGeneralTable::findOrFail($id);
            $company_id = $request->company_id ? (int) decode_id($request->company_id) : null;
            $record->update([
                'number_of_weapons' => $request->number_of_weapons,
                'slip_no' => $request->slip_no,
                'money_amount' => $request->money_amount,
                'slip_date' => $request->slip_date,
                'company_id' => $company_id,
                'created_by' => userid(),
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('weapons/attachments/' . date('Y') . '/' . date('m'), 'public');

                        Attachments::create([
                            'parent_id' => $id,
                            'file_name' => $file->getClientOriginalName(),
                            'file_size' => $file->getSize(),
                            'form_code' => 'frm-7',
                            'path_name' => $path,
                            'created_by' => userid(),
                            'created_department' => departmentId(),
                            'created_location' => locationId(),
                        ]);
                    }
                }
            }

            DB::commit();
            return response([
                'message' => 'Record successfully updated!',
                'id' => encode_id($record->id),
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response(['message' => 'An error occurred while updating the record.'], 500);
        }
    }

    protected function view($id)
    {
        $id = (int) $id;
        $record = WeaponsGeneralTable::join('users', 'users.id', '=', 'weapons_general_tables.created_by')
            ->join('departments', 'departments.id', '=', 'weapons_general_tables.created_department')
            ->join('provinces', 'provinces.id', '=', 'weapons_general_tables.created_location')
            ->select(
                'weapons_general_tables.*',
                'users.name as createdBy',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment'
            )
            ->where('weapons_general_tables.id', $id)
            ->firstOrFail();
        return $record;
    }

    protected function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:weapons_general_tables,id',
            'status' => 'required|boolean',
            'reason_dismissed' => 'required|string'
        ]);

        $weapon = WeaponsGeneralTable::find($request->input('id'));
        if (!$weapon) {
            return response()->json([
                'message' => 'weapon not found.',
            ], 404);
        }
        $parent_id = $weapon->id;
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('weapons/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments();
                    $attRecord->parent_id = $parent_id;
                    $attRecord->file_name = $file->getClientOriginalName();
                    $attRecord->file_size = $file->getSize();
                    $attRecord->form_code = 'frm-7';
                    $attRecord->path_name = $path;
                    $attRecord->created_by = userid();
                    $attRecord->save();
                }
            }
        }

        $weapon->status = (int) $request->input('status');
        $weapon->reason_dismissed = $request->input('reason_dismissed');
        $weapon->save();
        return response()->json([
            'message' => 'Status updated successfully.',
        ], 200);
    }
}
