<?php

namespace App\Http\Controllers\rms;

use App\Http\Controllers\Controller;
use App\Http\Resources\rms\EmployeeResource;
use App\Models\Auth\Attachments;
use App\Models\rms\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware('permission:employee-list')->only('index');
        $this->middleware('permission:employee-create')->only('store');
        $this->middleware('permission:employee-view')->only('view');
        $this->middleware('permission:employee-edit')->only('update');
        $this->middleware('permission:employee-status')->only('changeStatus');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected array $sortFields = [
        'employees.id',
        'employees.name',
        'employees.last_name',
        'employees.phone',
        'employees.created_at'
    ];

    protected function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->has('company_id') ? decode_id($request->company_id) : null;

        $query = Employee::join('users', 'users.id', 'employees.created_by')
            ->select(
                'employees.id',
                'employees.name',
                'employees.f_name',
                'employees.g_f_name',
                'employees.last_name',
                'employees.status',
                'employees.phone',
                'employees.none_criminal_record',
                'employees.none_criminal_record_info',
                'employees.country',
                'employees.type_residence_info',
                'employees.main_province',
                'employees.main_district',
                'employees.main_village',
                'employees.current_province',
                'employees.current_district',
                'employees.current_village',
                'employees.created_at',
                'users.name as ownerName'
            )
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('employees.company_id', $companyId);
            })
            ->when($request->name != '', function ($query) use ($request) {
                return $query->where('employees.name', 'LIKE', '%' . trim($request->name) . '%');
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
        $record = new Employee;
        $record->name = $request->name;
        $record->last_name = $request->last_name;
        $record->f_name = $request->f_name;
        $record->g_f_name = $request->g_f_name;
        $record->phone = $request->phone;
        $record->none_criminal_record = $request->none_criminal_record;
        $record->none_criminal_record_info = $request->none_criminal_record_info;
        $record->country = $request->country;
        $record->type_residence_info = $request->type_residence_info;
        $record->main_province = $request->main_province;
        $record->main_district = $request->main_district;
        $record->main_village = $request->main_village;
        $record->current_province = $request->current_province;
        $record->current_district = $request->current_district;
        $record->current_village = $request->current_village;
        $record->company_id = (int) $company_id;
        $record->created_by = userid();
        $record->created_department = departmentId();
        $record->created_location = locationId();
        $record->save();

        $parent_id = $record->id;
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment->isValid()) {
                    $path = $attachment->store('employees/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments;
                    $attRecord->parent_id = $parent_id;
                    $attRecord->file_name = $attachment->getClientOriginalName();
                    $attRecord->file_size = $attachment->getSize();
                    $attRecord->form_code = 'frm-03';
                    $attRecord->path_name = $path;
                    $attRecord->created_by = userid();
                    $attRecord->save();
                }
            }
        }

        return response([
            'message' => 'Employee record successfully saved!',
            'id' => encode_id($parent_id),
        ], 200);
    }

    protected function view($id)
    {
        $employee = Employee::join('users', 'users.id', '=', 'employees.created_by')
            ->join('provinces', 'provinces.id', '=', 'employees.created_location')
            ->join('departments', 'departments.id', '=', 'employees.created_department')
            ->leftJoin('provinces as main_province', 'main_province.id', '=', 'employees.main_province')
            ->leftJoin('districts as main_district', 'main_district.id', '=', 'employees.main_district')
            ->leftJoin('provinces as current_province', 'current_province.id', '=', 'employees.current_province')
            ->leftJoin('districts as current_district', 'current_district.id', '=', 'employees.current_district')
            ->select(
                'employees.id',
                'employees.name',
                'employees.last_name',
                'employees.f_name',
                'employees.g_f_name',
                'employees.phone',
                'employees.none_criminal_record',
                'employees.none_criminal_record_info',
                'employees.country',
                'employees.type_residence_info',
                'employees.main_province',
                'employees.main_district',
                'employees.main_village',
                'employees.current_province',
                'employees.current_district',
                'employees.current_village',
                'employees.status',
                'employees.created_at',
                'users.name as ownerName',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
                'main_province.name_dr as mainProvince',
                'main_district.district_dr as mainDistrict',
                'current_province.name_dr as currentProvince',
                'current_district.district_dr as currentDistrict'
            )
            ->where('employees.id', $id)
            ->firstOrFail();

        return new EmployeeResource($employee);
    }

    protected function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = (int) $id;
            $employee = Employee::findOrFail($id);

            $employee->update([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'f_name' => $request->f_name,
                'g_f_name' => $request->g_f_name,
                'phone' => $request->phone,
                'country' => $request->country,
                'type_residence_info' => $request->type_residence_info,
                'main_province' => $request->main_province,
                'main_district' => $request->main_district,
                'main_village' => $request->main_village,
                'current_province' => $request->current_province,
                'current_district' => $request->current_district,
                'current_village' => $request->current_village,
                'none_criminal_record' => $request->none_criminal_record,
                'none_criminal_record_info' => $request->none_criminal_record_info,
                'company_id' => $request->company_id ? (int) decode_id($request->company_id) : null,
            ]);


            if ($request->hasFile('attachments')) {
                $countAtt = count($request->file('attachments'));
                for ($i = 0; $i < $countAtt; $i++) {
                    if ($request->file('attachments')[$i]->isValid()) {
                        $path = $request->file('attachments')[$i]->store('employees/attachments/' . date('Y') . '/' . date('m'), 'public');
                        $attRecord = new Attachments;
                        $attRecord->parent_id = $id;
                        $attRecord->file_name = $request->file('attachments')[$i]->getClientOriginalName();
                        $attRecord->file_size = $request->file('attachments')[$i]->getSize();
                        $attRecord->form_code = 'frm-03';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }
            DB::commit();
            return response([
                'message' => 'Record successfully updated!',
                'id' => encode_id($employee->id),
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response(['message' => 'An error occurred while updating the record.'], 500);
        }
    }

    protected function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:employees,id',
            'status' => 'required|boolean',
        ]);

        $employee = Employee::find($request->input('id'));
        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found.',
            ], 404);
        }
        $employee->status = (int)$request->input('status');
        $employee->save();

        return response()->json([
            'message' => 'Status updated successfully.',
        ], 200);
    }
}
