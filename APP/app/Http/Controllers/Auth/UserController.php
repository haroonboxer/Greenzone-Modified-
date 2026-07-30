<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\Authentication\userResource;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Auth\System;
use App\Models\Auth\UserSystem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list')->only('index');
        $this->middleware('permission:user-create')->only('store');
        $this->middleware('permission:user-edit')->only('update');
        $this->middleware('permission:user-destroy')->only('destroy');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected function view($id = 0)
    {
        $record = User::join('departments', 'departments.id', 'users.department_id')
            ->join('provinces', 'provinces.id', 'users.location_id')
            ->select(
                'users.name',
                'users.username',
                'users.email',
                'users.image',
                'users.signature',
                'users.deleted_at',
                'departments.name_da as departmentName',
                'provinces.name_dr as provinceName',
            )
            ->where('users.id', $id)
            ->withTrashed()
            ->first();
        $system = System::join('user_systems', 'user_systems.system_id', 'systems.id')
            ->select(
                'systems.name_da as name',
            )
            ->where('user_systems.user_id', $id)
            ->get();
        $role = Role::join('user_systems', 'user_systems.system_id', 'roles.system_id')
            ->join('model_has_roles', 'model_has_roles.role_id', 'roles.id')
            ->select(
                'roles.id',
                'roles.name',
            )
            ->groupBy('roles.id', 'roles.name')
            ->where('model_has_roles.model_id', $id)
            ->get();
        return response([
            'record' => $record,
            'userSystem' => $system,
            'role' => $role,
        ], 200);
    }

    protected function edit($id = 0)
    {
        $record = User::find($id);
        $userSystem = UserSystem::join('systems', 'systems.id', 'user_systems.system_id')
            ->select(
                'systems.id as value',
                'systems.name_da as label',
            )
            ->where('user_systems.user_id', $id)
            ->get();
        $role = Role::join('user_systems', 'user_systems.system_id', 'roles.system_id')
            ->join('model_has_roles', 'model_has_roles.role_id', 'roles.id')
            ->select(
                'roles.id as value',
                'roles.name as label',
            )
            ->groupBy('roles.id', 'roles.name')
            ->where('model_has_roles.model_id', $id)
            ->get();
        return response([
            'record' => $record,
            'userSystem' => $userSystem,
            'role' => $role,
        ], 200);
    }
    protected array $sortFields = ['users.id', 'users.name', 'users.username', 'users.email', 'users.status', 'users.department_id', 'users.location_id'];

    protected function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $query = User::join('departments', 'departments.id', 'users.department_id')
            ->join('provinces', 'provinces.id', 'users.location_id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'users.status',
                'users.created_at',
                'users.deleted_at',
                'departments.name_da as departmentName',
                'provinces.name_dr as provinceName',
            )
            ->orderBy($sortField, $sortOrder)
            ->withTrashed()
            ->when($request->search != '', function ($query) use ($request) {
                return $query->where('users.name', 'LIKE', '%' . trim($request->search) . '%')
                    ->orWhere('users.username', 'LIKE', '%' . trim($request->search) . '%');
            });
        $perPage = $request->input('per_page') ?? self::PER_PAGE;
        $records = $query->paginate((int) $perPage);
        return userResource::collection($records);

    }

    protected function create()
    {
        $data['directorates'] = get_directorate();
        $data['provinces'] = get_allProvinces();
        $data['systems'] = get_all_systems();
        return view('auth.user.create', $data);
    }

    protected function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'username' => 'required|unique:users',
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:6',
                'department_id' => 'required:integer',
                'location_id' => 'required:integer',
                'system_id' => 'required:array',
                'roles' => 'required:array',
                'image' => $request->file('image') ? 'mimes:jpeg,bmp,jpg,png|max:2000' : '',
                'signature' => $request->file('signature') ? 'mimes:jpeg,bmp,jpg,png|max:2000' : '',
            ]
        );
        DB::beginTransaction();
        try {
            if ($request->file('image')) {
                $imagePath = Storage::disk('attachments')->put('user/' . date('Y') . '/' . date('m'), $request->file('image'));
            }
            if ($request->file('signature')) {
                $signaturePath = Storage::disk('attachments')->put('user/' . date('Y') . '/' . date('m'), $request->file('signature'));
            }
            $user = new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->image = $request->file('image') ? $imagePath : '';
            $user->signature = $request->file('signature') ? $signaturePath : '';
            $user->department_id = $request->department_id;
            $user->location_id = $request->department_id;
            $user->status = 'active';
            $user->created_by = uid();
            $user->save();
            foreach ($request->system_id as $key => $value) {
                UserSystem::create([
                    'user_id' => $user->id,
                    'system_id' => $request->system_id[$key]
                ]);
            }
            $user->assignRole($request->roles);
            DB::commit();
            if ($user) {
                return response([
                    'message' => 'ریکارد شما موفقانه ثبت گردید!',
                ], 200);
            } else {
                return response([
                    'message' => 'رمز شما موفقانه تغیر نمود!',
                ], 401);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response('Record not saved please try again!', 200);
        }
    }

    protected function update(Request $request, $id = 0)
    {
        $this->validate(
            $request,
            [
                'username' => 'required|unique:users,username,' . $id,
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => $request->password != '' ? 'required|confirmed|min:6' : '',
                'department_id' => 'required',
                'location_id' => 'required',
                'system_id' => 'required:array',
                'roles' => 'required:array',
                'image' => $request->file('image') ? 'mimes:jpeg,bmp,jpg,png|max:2000' : '',
                'signature' => $request->file('signature') ? 'mimes:jpeg,bmp,jpg,png|max:2000' : '',
            ]
        );
        DB::beginTransaction();
        try {
            if ($request->file('image')) {
                $imagePath = Storage::disk('attachments')->put('user/' . date('Y') . '/' . date('m'), $request->file('image'));
            }
            if ($request->file('signature')) {
                $signaturePath = Storage::disk('attachments')->put('user/' . date('Y') . '/' . date('m'), $request->file('signature'));
            }
            $user = User::findOrFail($id);

            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password ? Hash::make($request->password) : $user->password;
            $user->image = $request->file('image') ? $imagePath : $user->image;
            $user->signature = $request->file('signature') ? $signaturePath : $user->signature;
            $user->department_id = $request->department_id;
            $user->location_id = $request->location_id;
            $user->update();
            UserSystem::where('user_id', $user->id)->delete();
            foreach ($request->system_id as $key => $value) {
                UserSystem::create([
                    'user_id' => $user->id,
                    'system_id' => $request->system_id[$key]
                ]);
            }
            foreach ($user->roles as $key => $value) {
                $user->removeRole($value);
            }
            $user->assignRole($request->roles);
            DB::commit();
            // all good
            return response([
                'message' => 'ریکارد شما موفقانه ثبت گردید!',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response([
                'message' => 'رمز شما موفقانه تغیر نمود!',
            ], 401);
        }
    }

    protected function updateNew(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $request->id,
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => $request->password != '' ? 'required|confirmed|min:6' : '',
        ], [
            'username.required' => trans('words.Required_', ['name' => trans('words.Username')]),
            'username.unique' => trans('words.Duplicate Entry'),
            'name.required' => trans('words.Required_', ['name' => trans('words.Name')]),
            'email.required' => trans('words.Required_', ['name' => trans('words.Email')]),
            'email.email' => trans('words.Email Format'),
            'email.unique' => trans('words.Duplicate Entry'),
            'password.required' => trans('words.Required_', ['name' => trans('words.Password')]),
            'password.min' => trans('words.Min Password 6 Characters'),
            'password.confirmed' => trans('words.Password Confirmation'),
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors()->toArray());
        }

        DB::beginTransaction();

        try {
            $user = User::findOrFail($request->id);
            $user->username = $request->username;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->department = $request->department;
            if ($request->password != '') {
                $user->password = Hash::make($request->password);
            }
            $user->updated_by = uid();
            $user->update();

            UserSystem::where('user_id', $user->id)->delete();
            foreach ($request->system_id as $key => $value) {
                UserSystem::create([
                    'user_id' => $user->id,
                    'system_id' => $request->system_id[$key]
                ]);
            }

            foreach ($user->roles as $key => $value) {
                $user->removeRole($value->name);
            }

            $user->assignRole($request->roles);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('warning', trans('words.Please Try Again'));
            return false;
        }
    }

    protected function changeStatus($id = 0, $status = 0)
    {

        $status == 1 ? User::findOrFail($id)->delete() : User::withTrashed()->find($id)->restore();
        return response(
            $status == 1 ? 'User successfully disabled!' : 'User successfully enabled!'
        );
    }

    protected function changePassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'newPassword' => 'required',
                'confirmPassword' => 'required|same:newPassword'
            ],
            [
                'newPassword.required' => "درج رمز جدید ضروری میباشد!",
                'confirmPassword.required' => "درج رمز جدید ضروری میباشد!",
                'confirmPassword.save' => "رمز با تایید رمز مطابقت ندارد!"
            ]
        );
        $user = User::findOrFail(userid());
        $user->password = Hash::make($request->newPassword);
        $user->update();
        return response([
            'status' => true,
            'message' => 'رمز شما موفقانه تغیر نمود!',
        ], 200);
    }
    protected function changeUserImage(Request $request)
    {
        if ($request->has('profile_avatar')) {
            $user = User::findOrFail(auth()->id());
            $img = Storage::disk('user_images')->put(date('Y') . '/' . date('m'), $request->file('profile_avatar'));
            $user->image = $img;
            $user->updated_by = uid();
            $user->update();
            return true;
        } else {
            return false;
        }
    }
    protected function bringDepartmentDirectorateId(Request $request)
    {
        if ($request->ajax()) {
            $data = get_department_by_directorate($request->id);
            return view('reusable-balds.option', compact('data'));
        }
    }

    protected function bringSubDepartmentDepartmentId(Request $request)
    {
        if ($request->ajax()) {
            $data = get_sub_department_by_department_id($request->id);
            return view('reusable-balds.option', compact('data'));
        }
    }
    protected function bringRolesBySystemId(Request $request)
    {
        if ($request->ajax()) {
            $data = bringRolesBySystemId($request->id);
            return view('reusable-balds.option', compact('data'));
        }
    }

    protected function restore($id = 0)
    {
        User::withTrashed()->find($id)->restore();
        session()->flash('success', __("global.success_msg"));
        return redirect()->route('users');
    }

    protected function authUser()
    {
        if (Auth::user()) {
            $user = User::join('departments', 'departments.id', 'users.department_id')
                ->join('provinces', 'provinces.id', 'users.location_id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.username',
                    'users.email',
                    'users.image',
                    'users.signature',
                    'departments.name_da as departmentName',
                    'provinces.name_dr as provinceName',
                )
                ->where('users.id', userid())
                ->first();
            return json_encode([
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'image' => $user->image,
                'signature' => $user->signature,
                'departmentName' => $user->departmentName,
                'provinceName' => $user->provinceName,
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'role' => $user->roles()->pluck('name'),
                'systems' => user_system(),
            ]);
        } else {
            return response(['message' => 'User not authenticated'], 401);
        }
    }
    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
    protected function changeUserPassword(Request $request, $id = 0)
    {
        $this->validate(
            $request,
            [
                'password' => 'required',
                'password_confirmation' => 'required|same:password'
            ]
        );
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->update();
        return response([
            'status' => true,
            'message' => 'رمز شما موفقانه تغیر نمود!',
        ], 200);
    }


    protected function refreshToken(Request $request)
    {
        $user = Auth::user();
        // $token = $user->createToken("API TOKEN")->plainTextToken;
        dd($user);
        // return response()->json([
        //     'refresh_token' => $token,
        //     'token_type' => 'Bearer',
        //     'expires_at' => now()->addMinutes(60), // Adjust expiration time
        // ]);

    }
}