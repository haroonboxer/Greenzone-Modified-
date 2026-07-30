<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Authentication\DirectorateResource;
use Auth;
use Illuminate\Http\Request;
use App\Models\Auth\Directorates;

class DirectorateController extends Controller
{
   public function __construct()
   {
      $this->middleware('permission:admin-list')->only('index');
      $this->middleware('permission:admin-create')->only('store');
      $this->middleware('permission:admin-edit')->only('update');
      $this->middleware('permission:admin-destroy')->only('destroy');
      $this->middleware(function ($request, $next) {
         $this->user = Auth::guard('web')->user();
         return $next($request);
      });
   }

   protected array $sortFields = ['directorates.id', 'directorates.name_da', 'directorates.name_pa', 'directorates.name_en', 'directorates.code', 'directorates.created_by'];
   protected function index(Request $request)
   {
      $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
      $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
      $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
      $searchInput = trim($request->input('search'));
      $query = Directorates::join('users', 'users.id', 'directorates.created_by')
         ->select(
            'directorates.id',
            'directorates.name_da',
            'directorates.name_pa',
            'directorates.name_en',
            'directorates.code',
            'users.name as ownerName',
         )
         ->orderBy($sortField, $sortOrder)
         ->when(request()->search, function ($query) {
            $query->where('name_da', 'like', '%' . trim(request()->search) . '%')
               ->orWhere('name_pa', 'like', '%' . trim(request()->search) . '%')
               ->orWhere('name_en', 'like', '%' . trim(request()->search) . '%');
         });
      $perPage = $request->input('per_page') ?? self::PER_PAGE;
      $records = $query->paginate((int) $perPage);
      return DirectorateResource::collection($records);
   }

   protected function create()
   {
      return view('auth.directorate.create');
   }

   protected function store(Request $request)
   {
      $this->validate(
         $request,
         [
            'name_fa' => ['required', 'string', 'max:64'],
            'name_pa' => ['required', 'string', 'max:64'],
            'name_en' => ['required', 'string', 'max:64'],
            'code' => ['required', 'string', 'max:64'],
         ],
         [
            'name_fa.required' => trans('global.rquired_', ['name' => trans('global.name_da')]),
            'name_pa.required' => trans('global.rquired_', ['name' => trans('global.name_pa')]),
            'name_en.required' => trans('global.rquired_', ['name' => trans('global.name_en')]),
            'code.required' => trans('global.rquired_', ['name' => trans('global.code')]),

         ]
      );
      $data = new Directorates;
      $data->name_da = $request->name_fa;
      $data->name_pa = $request->name_pa;
      $data->name_en = $request->name_en;
      $data->code = $request->code;
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

   protected function bringDistrictByProvinceId(Request $request)
   {
      if ($request->ajax()) {
         $district = BringDistricts($request->id);
         return view('reusable-balds.districts', compact('district'));
      }
   }
   protected function edit($id = 0)
   {
      $data = Directorates::find($id);
      return response($data, 200);
   }

   protected function update(Request $request, $id = 0)
   {
      $this->validate(
         $request,
         [
            'name_fa' => 'required',
            'name_pa' => 'required',
            'name_en' => 'required',
            'code' => 'required',
         ],
         [
            'name_fa.required' => trans('global.rquired_', ['name' => trans('global.name_da')]),
            'name_pa.required' => trans('global.rquired_', ['name' => trans('global.name_pa')]),
            'name_en.required' => trans('global.rquired_', ['name' => trans('global.name_en')]),
            'code.required' => trans('global.rquired_', ['name' => trans('global.code')]),

         ]
      );
      $data = Directorates::findOrFail($id);
      $data->name_da = $request->name_fa;
      $data->name_pa = $request->name_pa;
      $data->name_en = $request->name_en;
      $data->code = $request->code;
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

   protected function getDirectorates()
   {
      $data = get_directorate();
      return response([
         'data' => $data
      ], 200);
   }
}