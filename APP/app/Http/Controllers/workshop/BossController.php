<?php

namespace App\Http\Controllers\workshop;

use App\Http\Controllers\Controller;
use App\Http\Requests\workshop\WorkshopBossRequest;
use App\Http\Resources\workshop\workshopBossResource;
use App\Models\Auth\Attachments;
use App\Models\workshop\workshopBoss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BossController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware('permission:workshop-boss-list')->only('index');
        $this->middleware('permission:workshop-boss-create')->only('store');
        $this->middleware('permission:workshop-boss-view')->only('view');
        $this->middleware('permission:workshop-boss-edit')->only('edit');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }
    protected array $sortFields = [
        'workshop_bosses.id',
        'workshop_bosses.name_dr',
        'workshop_bosses.last_name_dr',
        'workshop_bosses.status',
        'workshop_bosses.created_at'
    ];

    protected function index(Request $request)
    {

        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->has('company_id') ? decode_id($request->company_id) : null;
        $query = workshopBoss::join('users', 'users.id', 'workshop_bosses.created_by')
            ->select(
                'workshop_bosses.*',
                'workshop_bosses.photo',
                'users.name as ownerName'
            )
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('workshop_bosses.company_id', $companyId);
            })
            ->when($request->name_dr != '', function ($query) use ($request) {
                return $query->where('workshop_bosses.name_dr', 'LIKE', '%' . trim($request->name_dr) . '%');
            });
        $perPage = $request->input('per_page') ?? self::PER_PAGE;
        $records = $query->paginate((int) $perPage);
        return workshopBossResource::collection($records);
    }

    protected function store(WorkshopBossRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $company_id = $request->company_id ? decode_id($request->company_id) : null;
            if (!$company_id) {
                return response([
                    'message' => 'Invalid company ID.',
                ], 422);
            }
            $photoPath = '';

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('workshopBosses/photos/' . date('Y') . '/' . date('m'), 'public');
            }

            $record = new WorkshopBoss();
            $record->name_dr = $request->name_dr;
            $record->name_en = $request->name_en;
            $record->last_name_dr = $request->last_name_dr;
            $record->last_name_en = $request->last_name_en;
            $record->f_name_da = $request->f_name_da;
            $record->photo = $photoPath ? asset('storage/' . $photoPath) : '';
            $record->phone = $request->phone;
            $record->passport_no = $request->passport_no;
            $record->country = $request->country;
            $record->main_province = $request->main_province;
            $record->main_district = $request->main_district;
            $record->main_village = $request->main_village;
            $record->current_province = $request->current_province;
            $record->current_district = $request->current_district;
            $record->current_village = $request->current_village;
            $record->type_residence_info = $request->type_residence_info;
            $record->company_id = (int) decode_id($request->company_id);
            $record->status = $request->status ?? 1;
            $record->created_by = userid();
            $record->created_by_name =userName();
            $record->created_department = departmentId();
            $record->created_location = ProvinceId();
            $record->save();

            $parent_id = $record->id;

            if ($request->hasFile('attachments')) {
                $countAtt = count($request->file('attachments'));
                for ($i = 0; $i < $countAtt; $i++) {
                    if ($request->file('attachments')[$i]->isValid()) {
                        $path = $request->file('attachments')[$i]->store('workshopBosses/attachments/' . date('Y') . '/' . date('m'), 'public');
                        $attRecord = new Attachments();
                        $attRecord->parent_id = $parent_id;
                        $attRecord->file_name = $request->file('attachments')[$i]->getClientOriginalName();
                        $attRecord->file_size = $request->file('attachments')[$i]->getSize();
                        $attRecord->form_code = 'frm-W2';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }
            DB::commit();

            return response([
                'message' => 'Boss record successfully saved!',
                'id' => encode_id($parent_id),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'message' => $e,
            ], 500);
        }
    }

    protected function view($id = 0, Request $request)
    {
        if (!is_numeric($id)) {
            $id = decode_id($id);
        }

        $record = workshopBoss::join('users', 'users.id', '=', 'workshop_bosses.created_by')
            ->join('provinces', 'provinces.id', '=', 'workshop_bosses.created_location')
            ->join('departments', 'departments.id', '=', 'workshop_bosses.created_department')
            ->leftJoin('provinces as main_province', 'main_province.id', '=', 'workshop_bosses.main_province')
            ->leftJoin('districts as main_district', 'main_district.id', '=', 'workshop_bosses.main_district')
            ->leftJoin('provinces as current_province', 'current_province.id', '=', 'workshop_bosses.current_province')
            ->leftJoin('districts as current_district', 'current_district.id', '=', 'workshop_bosses.current_district')
            ->select(
                'workshop_bosses.*',
                'users.name as createdBy',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
                'main_province.name_dr as mainProvince',
                'main_district.district_dr as mainDistrict',
                'current_province.name_dr as currentProvince',
                'current_district.district_dr as currentDistrict'
            )
            ->find($id);

        return response()->json([
            'record' => $record,
        ], 200);
    }

    protected function update(Request $request, $id)
    {
        $id = (int) $id;
        DB::beginTransaction();
        try {
            $boss = workshopBoss::findOrFail($id);
            if (!$boss) {
                return response([
                    'message' => 'Boss not found.',
                ], 404);
            }
            $boss->update([
                'name_dr' => $request->name_dr,
                'name_en' => $request->name_en,
                'last_name_dr' => $request->last_name_dr,
                'last_name_en' => $request->last_name_en,
                'f_name_da' => $request->f_name_da,
                'email' => $request->email,
                'phone' => $request->phone,
                'passport_no' => $request->passport_no,
                'country' => $request->country,
                'main_province' => $request->main_province,
                'main_district' => $request->main_district,
                'main_village' => $request->main_village,
                'current_province' => $request->current_province,
                'current_district' => $request->current_district,
                'current_village' => $request->current_village,
                'type_residence_info' => $request->type_residence_info,
                'company_id' => $request->company_id ? (int) decode_id($request->company_id) : null,
            ]);


            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photoPath = $request->file('photo')->store('workshopBosses/photos/' . date('Y') . '/' . date('m'), 'public');
                $boss->photo = asset('storage/' . $photoPath);
                $boss->save();
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store(
                            'workshopBosses/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments();
                        $attRecord->parent_id = $id;
                        $attRecord->file_name = $file->getClientOriginalName();
                        $attRecord->file_size = $file->getSize();
                        $attRecord->form_code = 'frm-W2';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();
            return response([
                'message' => 'Record successfully updated!',
                'id' => encode_id($boss->id),
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response([
                'message' => 'An error occurred while updating the record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
