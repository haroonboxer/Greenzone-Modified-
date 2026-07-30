<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\Directorates;
use App\Models\Auth\Department;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin-view')->only('index');
        $this->middleware('permission:admin-create')->only('store');
        $this->middleware('permission:admin-edit')->only('update');
        $this->middleware('permission:admin-destroy')->only('destroy');
    }

    protected function index(Request $request)
    {
        $menuItem = Department::select(
            'id',
            'name_da as label',
            'department_parent as parentId'
        )
            ->groupBy('id', 'name_da', 'department_parent') 
            ->orderBy('id', 'ASC')
            ->get();

        $hierarchicalData = buildTree($menuItem->toArray(), 0);
        $hierarchicalData = array_map(function ($item) {
            unset($item['parent'], $item['org_type']);
            return $item;
        }, $hierarchicalData);

        $flatData = flattenHierarchy($hierarchicalData);
        return response($flatData, 200);
    }

    protected function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name_en' => ['required', 'string'],
                'name_da' => ['required', 'string'],
                'name_pa' => ['required', 'string'],
                'parent_id' => ['required', 'integer'],
            ]
        );
        $data = new Department;
        $data->department_parent = $request->parent_id;
        $data->name_en = $request->name_en;
        $data->name_da = $request->name_da;
        $data->name_pa = $request->name_pa;
        $data->org_type = 1;
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
        $department = Department::find($id);
        $directorates = Directorates::select('id', 'name_da as name')->get();
        return response(['department' => $department, 'directorates' => $directorates], 200);
    }

    protected function update(Request $request, $id = 0)
    {
        $this->validate(
            $request,
            [
                'directorate_id' => 'required',
                'name_da' => 'required',
                'name_pa' => 'required',
            ],
            [
                'directorate_id.required' => trans('global.selectRequired_', ['name' => trans('global.directorate')]),
                'name_da.required' => trans('global.rquired_', ['name' => trans('global.name_da')]),
                'name_pa.required' => trans('global.rquired_', ['name' => trans('global.name_pa')]),
            ]
        );
        $data = Department::findOrFail($id);
        $data->directorate_id = $request->directorate_id;
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
    protected function geAllDepartmentInOption()
    {
        $departments = Department::select('id as value', 'name_da as label')->get();
        return response($departments);
    }
    protected function getAllProvinces()
    {
        $provinces = get_allProvinces();
        return response($provinces);
    }
}
