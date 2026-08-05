<?php

namespace App\Http\Controllers\green_zone;

use App\Http\Controllers\Controller;
use App\Http\Resources\green_zone\VehicleSaveResource;
use App\Models\green_zone\VehicleSave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Nette\Schema\Message;

class VehicleSaveController extends Controller
{
    protected $user;

    public function __construct()
    {
        // $this->middleware('permission:vehicle-list')->only('index');
        // $this->middleware(function ($request, $next) {
        //     $this->user = Auth::guard('web')->user();
        //     return $next($request);
        // });
    }

    protected array $sortFields = [
        'vehicle_saves.id',
        'vehicle_saves.name',
        'vehicle_saves.created_at'
    ];

    public function index(Request $request)
    {

        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);

        $query = VehicleSave::join('users', 'users.id', '=', 'vehicle_saves.created_by')
            ->select(
                'vehicle_saves.*',
                'users.name as ownerName'
            )
            ->orderBy($sortField, $sortOrder)
            ->when($request->filled('search'), function ($query) use ($request) {
                return $query->where('vehicle_saves.name', 'LIKE', '%' . trim($request->search) . '%');
            });

        $perPage = (int) $request->input('per_page', self::PER_PAGE);

        $records = $query->paginate($perPage);

        return VehicleSaveResource::collection($records);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $vehicleSave = new VehicleSave();
            $vehicleSave->name = $request->name;
            $vehicleSave->created_by = 1; //userid();
            $vehicleSave->created_department = 1; //departmentId();
            $vehicleSave->created_location = 1; //locationId();
            $vehicleSave->save();

            DB::commit();

            return response([
                'message' => 'Vehicle record successfully saved!',
                'id' => encode_id($vehicleSave->id),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response([

                'error' => $e->getMessage(),
                'sso' => userid(),
            ], 500);
            // return response([
            //     'error'=>$e->getMessage(),
            //     'user'=>Auth::guard('sso')->user(),
            //     'id'=>Auth::guard('sso')->id()
            // ],500);// request()->bearerToken(); //,
            //response(['message' => Auth::guard('sso')->id(),], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $vehicleSave = VehicleSave::findOrFail($id);
            $vehicleSave->name = $request->name;
            $vehicleSave->save();

            DB::commit();

            return response([
                'message' => 'Vehicle record successfully updated!',
                'id' => encode_id($vehicleSave->id),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
