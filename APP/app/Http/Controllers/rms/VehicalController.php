<?php

namespace App\Http\Controllers\rms;

use App\Http\Controllers\Controller;
use App\Http\Resources\rms\VehicalResource;
use App\Models\Auth\Attachments;
use App\Models\rms\Vehical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehicalController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('permission:vehical-list')->only('index');
        $this->middleware('permission:vehical-create')->only('store');
        $this->middleware('permission:vehical-view')->only('view');
        $this->middleware('permission:vehical-edit')->only('update');
        $this->middleware('permission:vehical-status')->only('changeStatus');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected function index(Request $request)
    {
        $records = Vehical::join('users', 'users.id', '=', 'vehicals.created_by')
            ->select(
                'vehicals.id',
                'vehicals.vehical_type',
                'vehicals.vehical_ownership',
                'vehicals.vehical_platte_no',
                'vehicals.vehical_color',
                'vehicals.engine_no',
                'vehicals.shasi_no',
                'vehicals.license_start_date',
                'vehicals.license_end_date',
                'vehicals.status',
                'vehicals.created_at',
                'users.name as ownerName'
            )
            ->when($request->filled('company_id'), function ($query) use ($request) {
                return $query->where('vehicals.company_id', decode_id($request->company_id));
            });

        $perPage = (int) ($request->input('per_page') ?? 10);
        $paginated = $records->paginate($perPage);

        return VehicalResource::collection($paginated);
    }


    protected function store(Request $request)
    {
         
        $company_id = $request->company_id ? decode_id($request->company_id) : null;

        if (!$company_id) {
            return response(['message' => 'Invalid company ID.'], 422);
        }

        $vehical = new Vehical;
        $vehical->vehical_type = $request->vehical_type;
        $vehical->vehical_ownership = $request->vehical_ownership;
        $vehical->vehical_platte_no = $request->vehical_platte_no;
        $vehical->vehical_color = $request->vehical_color;
        $vehical->engine_no = $request->engine_no;
        $vehical->shasi_no = $request->shasi_no;
        $vehical->license_start_date = $request->license_start_date;
        $vehical->license_end_date = $request->license_end_date;
        $vehical->company_id = (int) $company_id;
        $vehical->created_by = userid();
        $vehical->created_by_name = userName();
        $vehical->created_department = departmentId();
        $vehical->created_location = ProvinceId();
        $vehical->save();

        $vehical_id = $vehical->id;

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment->isValid()) {
                    $path = $attachment->store('vehicals/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments();
                    $attRecord->parent_id = $vehical_id;
                    $attRecord->file_name = $attachment->getClientOriginalName();
                    $attRecord->file_size = $attachment->getSize();
                    $attRecord->form_code = 'frm-08';
                    $attRecord->path_name = $path;
                    $attRecord->created_by = userid();
                    $attRecord->save();
                }
            }
        }

        return response([
            'message' => 'Vehical record successfully saved!',
            'id' => encode_id($vehical_id),
        ], 200);
    }

    protected function view($id)
    {

        $vehical = Vehical::join('users', 'users.id', '=', 'vehicals.created_by')
            ->join('provinces', 'provinces.id', '=', 'vehicals.created_location')
            ->join('departments', 'departments.id', '=', 'vehicals.created_department')
            ->select(
                'vehicals.*',
                'users.name as ownerName',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
            )
            ->where('vehicals.id', $id)
            ->firstOrFail();

        return new VehicalResource($vehical);
    }

    protected function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $vehical = Vehical::findOrFail((int) $id);

            $vehical->update([
                'vehical_type' => $request->vehical_type,
                'vehical_ownership' => $request->vehical_ownership,
                'vehical_platte_no' => $request->vehical_platte_no,
                'vehical_color' => $request->vehical_color,
                'engine_no' => $request->engine_no,
                'shasi_no' => $request->shasi_no,
                'license_start_date' => $request->license_start_date,
                'license_end_date' => $request->license_end_date,
                'company_id' => $request->company_id ? (int) decode_id($request->company_id) : null,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('vehicals/attachments/' . date('Y') . '/' . date('m'), 'public');

                        $attRecord = new Attachments;
                        $attRecord->parent_id = $vehical->id;
                        $attRecord->file_name = $file->getClientOriginalName();
                        $attRecord->file_size = $file->getSize();
                        $attRecord->form_code = 'frm-veh';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();
            return response([
                'message' => 'Record successfully updated!',
                'id' => encode_id($vehical->id),
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
            'id' => 'required|numeric|exists:vehicals,id',
            'status' => 'required',
        ]);

        $vehical = Vehical::find($request->input('id'));
        if (!$vehical) {
            return response()->json(['message' => 'Vehical not found.'], 404);
        }

        $vehical->status = (int) $request->input('status');
        $vehical->save();

        return response()->json(['message' => 'Status updated successfully.'], 200);
    }

    protected function createButton(Request $request)
    {
        $companyId = $request->has('id') ? decode_id($request->id) : null;
        $query = Vehical::select('vehicals.status')
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('vehicals.company_id', $companyId)
                    ->where('vehicals.status', 1);
            });
        $records = $query->get();
        return VehicalResource::collection($records);
    }
}
