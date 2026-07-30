<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Authentication\SubDepartmentResource;
use Auth;
use Illuminate\Http\Request;
use App\Models\Auth\Directorates;
use App\Models\Auth\Department;
use App\Models\Auth\SubDepartment;
use DataTables;

class SubDepartmentController extends Controller
{
   public function __construct()
   {
      $this->middleware('permission:admin-list')->only('index');
      $this->middleware('permission:admin-create')->only('store');
      $this->middleware('permission:admin-edit')->only('update');
      $this->middleware('permission:admin-destroy')->only('destroy');
   }

   protected array $sortFields = ['sub_departments.id', 'sub_departments.name_da', 'sub_departments.name_pa', 'sub_departments.directorate_id', 'sub_departments.department_id', 'sub_departments.created_by'];
   protected function index(Request $request)
   {
      $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
      $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
      $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
      $query = SubDepartment::join('users', 'users.id', 'sub_departments.created_by')
         ->join('directorates', 'directorates.id', 'sub_departments.directorate_id')
         ->join('departments', 'departments.id', 'sub_departments.department_id')

         ->select(
            'sub_departments.id',
            'sub_departments.name_da',
            'sub_departments.name_pa',
            'directorates.name_da as directorateName',
            'departments.name_da as departmentName',
            'users.name as ownerName',
         )
         ->orderBy($sortField, $sortOrder)
         ->when(request()->search, function ($query) {
            $query->where('sub_departments.name_da', 'like', '%' . trim(request()->search) . '%')
               ->orWhere('sub_departments.name_pa', 'like', '%' . trim(request()->search) . '%');
         })
         ->when(request()->departments, function ($query) {
            $query->where('sub_departments.directorate_id', trim(request()->departments));
         });
      $perPage = $request->input('per_page') ?? self::PER_PAGE;
      $records = $query->paginate((int) $perPage);
      return SubDepartmentResource::collection($records);
   }


   protected function create()
   {
      $directorates = Directorates::select('id', 'name_da as name')->get();
      return view('auth.sub-department.create', compact('directorates'));
   }

   protected function store(Request $request)
   {
      $this->validate(
         $request,
         [
            'directorate_id' => 'required',
            'department_id' => 'required',
            'name_da' => 'required',
            'name_pa' => 'required',
         ],
         [
            'directorate_id.required' => trans('global.selectRequired_', ['name' => trans('global.directorate')]),
            'department_id.required' => trans('global.selectRequired_', ['name' => trans('global.department')]),
            'name_da.required' => trans('global.rquired_', ['name' => trans('global.name_da')]),
            'name_pa.required' => trans('global.rquired_', ['name' => trans('global.name_pa')]),
         ]
      );
      $data = new SubDepartment;
      $data->directorate_id = $request->directorate_id;
      $data->department_id = $request->department_id;
      $data->name_da = $request->name_da;
      $data->name_pa = $request->name_pa;
      $data->created_by = userid();
      if ($data->save()) {
         return response([
            'message' => 'ریکارد شما موفقانه ثبت گردید!',
         ], 200);
      } else {
         return response([
            'message' => 'رمز شما موفقانه تغیر نمود!',
         ], 401);
      }
   }

   protected function edit($id = 0)
   {
      $subDepartment = SubDepartment::find($id);
      $directorates = Directorates::select('id', 'name_da as name')->get();
      $departments = get_department_by_directorate($subDepartment->directorate_id);
      return response(['directorates' => $directorates, 'departments' => $departments, 'subDepartment' => $subDepartment], 200);

   }

   protected function update(Request $request, $id = 0)
   {
      $this->validate(
         $request,
         [
            'directorate_id' => 'required',
            'department_id' => 'required',
            'name_da' => 'required',
            'name_pa' => 'required',
         ],
         [
            'directorate.required' => trans('global.selectRequired_', ['name' => trans('global.directorate')]),
            'department.required' => trans('global.selectRequired_', ['name' => trans('global.department')]),
            'name_da.required' => trans('global.rquired_', ['name' => trans('global.name_da')]),
            'name_pa.required' => trans('global.rquired_', ['name' => trans('global.name_pa')]),
         ]
      );
      $data = SubDepartment::findOrFail($id);
      $data->directorate_id = $request->directorate_id;
      $data->department_id = $request->department_id;
      $data->name_da = $request->name_da;
      $data->name_pa = $request->name_pa;
      if ($data->update()) {
         return response([
            'message' => 'ریکارد شما موفقانه ثبت گردید!',
         ], 200);
      } else {
         return response([
            'message' => 'رمز شما موفقانه تغیر نمود!',
         ], 401);
      }
   }
}