<?php

namespace App\Http\Controllers\rms;

use App\Http\Controllers\Controller;
use App\Http\Resources\rms\GunResource;
use App\Models\rms\Gun;
use App\Models\Auth\Attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GunController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:gun-list')->only('index');
        $this->middleware('permission:gun-create')->only('store');
        $this->middleware('permission:gun-edit')->only('update');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }
    protected array $sortFields = [
        'guns.id',
        'guns.gun_no',
        'guns.gun_type',
        'guns.gun_diameter',
        'guns.gun_country',
        'guns.created_at'
    ];

    protected function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->filled('company_id') ? ($request->input('company_id')) : null;
        $weaponId = $request->filled('weapon_id') ? ($request->input('weapon_id')) : null;
        $query = Gun::join('users', 'users.id', '=', 'guns.created_by')
            ->select(
                'guns.id',
                'guns.gun_no',
                'guns.gun_type',
                'guns.gun_diameter',
                'guns.taedad_jabeh',
                'guns.gun_country',
                'guns.created_at',
                'users.name as ownerName',
            )
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('guns.company_id', $companyId);
            })
            ->when($weaponId, function ($query) use ($weaponId) {
                return $query->where('guns.weapon_id', $weaponId);
            })
            ->when($request->filled('gun_no'), function ($query) use ($request) {
                return $query->where('guns.gun_no', 'LIKE', '%' . trim($request->input('gun_no')) . '%');
            });
        $perPage = (int) $request->input('per_page', self::PER_PAGE);
        $records = $query->paginate($perPage, 5);
        return GunResource::collection($records);
    }

    protected function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $company_id = $request->company_id ? (int) ($request->company_id) : null;
            $weapon_id = $request->weapon_id ? (int)($request->weapon_id) : null;
            $created_by = userid();
            $created_by_name = userName();
            $created_department = departmentId();
            $created_location = ProvinceId();

            $created_guns = [];

            foreach ($request->guns as $gunData) {
                $record = Gun::create([
                    'gun_no' => $gunData['gun_no'],
                    'gun_type' => $gunData['gun_type'] ?? null,
                    'gun_diameter' => $gunData['gun_diameter'],
                    'taedad_jabeh' => $gunData['taedad_jabeh'] ?? null,
                    'gun_country' => $gunData['gun_country'] ?? null,
                    'company_id' => $company_id,
                    'weapon_id' => $weapon_id,
                    'created_by' => $created_by,
                    'created_department' => $created_department,
                    'created_location' => $created_location,
                    'created_by_name' =>$created_by_name,
                ]);

                $created_guns[] = $record->id;

                if ($request->hasFile("attachments")) {
                    foreach ($request->file("attachments") as $file) {
                        if ($file->isValid()) {
                            $path = $file->store('weapons/attachments/' . date('Y') . '/' . date('m'), 'public');

                            $attRecord = new Attachments;
                            $attRecord->parent_id = $record->id;
                            $attRecord->file_name = $file->getClientOriginalName();
                            $attRecord->file_size = $file->getSize();
                            $attRecord->form_code = 'frm-7';
                            $attRecord->path_name = $path;
                            $attRecord->created_by = $created_by;
                            $attRecord->save();
                        }
                    }
                }
            }

            DB::commit();

            return response([
                'message' => 'Guns successfully saved!',
                'ids' => array_map('encode_id', $created_guns),
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Error storing gun records: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response([
                'message' => 'An error occurred while saving the records.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    protected function update(Request $request, $id)
    {
        $id = (int) $id;
        DB::beginTransaction();
        try {
            $company_id = $request->company_id ? (int) $request->company_id : null;
            $weapon_id = $request->weapon_id ? (int) $request->weapon_id : null;
            $created_by = userid();
            $created_department = departmentId();
            $created_location = locationId();

            $record = Gun::findOrFail($id);
            $record->update([
                'gun_no' => $request->gun_no,
                'gun_type' => $request->gun_type,
                'gun_diameter' => $request->gun_diameter,
                'taedad_jabeh' => $request->taedad_jabeh,
                'gun_country' => $request->gun_country,
                'company_id' => $company_id,
                'weapon_id' => $weapon_id,
                'updated_by' => $created_by,
                'updated_department' => $created_department,
                'updated_location' => $created_location,
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
}
