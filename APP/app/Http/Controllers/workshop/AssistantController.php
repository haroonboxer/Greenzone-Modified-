<?php

namespace App\Http\Controllers\workshop;

use App\Http\Controllers\Controller;
use App\Http\Requests\rms\AssistantRequest;
use App\Http\Resources\workshop\AssistantResource;
use App\Models\Auth\Attachments;
use App\Models\rms\Assistant;
use App\Models\workshop\WorkshopAssistant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssistantController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:workshop-assistant-list')->only('index');
        $this->middleware('permission:workshop-assistant-create')->only('store');
        $this->middleware('permission:workshop-assistant-view')->only('view');
        $this->middleware('permission:workshop-assistant-edit')->only('update');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected array $sortFields = ['workshop_assistants.id', 'workshop_assistants.name_dr', 'workshop_assistants.email', 'workshop_assistants.status', 'workshop_assistants.created_at'];

    protected function index(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->filled('company_id') ? decode_id($request->input('company_id')) : null;

        $query = WorkshopAssistant::join('users', 'users.id', '=', 'workshop_assistants.created_by')
            ->select(
                'workshop_assistants.id',
                'workshop_assistants.name_dr',
                'workshop_assistants.name_en',
                'workshop_assistants.last_name_dr',
                'workshop_assistants.last_name_en',
                'workshop_assistants.f_name_da',
                'workshop_assistants.email',
                'workshop_assistants.phone',
                'workshop_assistants.passport_no',
                'workshop_assistants.country',
                'workshop_assistants.type_residence_info',
                'workshop_assistants.photo',
                'workshop_assistants.main_province',
                'workshop_assistants.main_district',
                'workshop_assistants.main_village',
                'workshop_assistants.current_province',
                'workshop_assistants.current_district',
                'workshop_assistants.current_village',
                'workshop_assistants.status',
                'workshop_assistants.created_at',
                'users.name as ownerName',
            )
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('workshop_assistants.company_id', $companyId);
            })
            ->when($request->filled('name_dr'), function ($query) use ($request) {
                return $query->where('workshop_assistants.name_dr', 'LIKE', '%' . trim($request->input('name_dr')) . '%');
            })
            ->when($request->filled('email'), function ($query) use ($request) {
                return $query->where('workshop_assistants.email', 'LIKE', '%' . trim($request->input('email')) . '%');
            });
        $perPage = (int) $request->input('per_page', self::PER_PAGE);
        $records = $query->paginate($perPage);
        // dd($records->items());
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

    protected function store(AssistantRequest $request)
    {

        DB::beginTransaction();
        try {
            $company_id = $request->company_id ? (int) decode_id($request->company_id) : null;

            $photoPath = null;
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photoPath = $request->file('photo')->store('assistant/photos/' . date('Y') . '/' . date('m'), 'public');
            }
            $record = WorkshopAssistant::create([
                'name_dr' => $request->name_dr,
                'name_en' => $request->name_en,
                'last_name_dr' => $request->last_name_dr,
                'last_name_en' => $request->last_name_en,
                'f_name_da' => $request->f_name_da,
                'email' => $request->email,
                'phone' => $request->phone,
                'passport_no' => $request->passport_no,
                'country' => $request->country,
                'type_residence_info' => $request->type_residence_info,
                'photo' => $photoPath ? asset('storage/' . $photoPath) : null,
                'main_province' => $request->main_province,
                'main_district' => $request->main_district,
                'main_village' => $request->main_village,
                'current_province' => $request->current_province,
                'current_district' => $request->current_district,
                'current_village' => $request->current_village,
                'company_id' => $company_id,
                'created_by' => userid(),
                'created_department' => departmentId(),
                'created_location' => locationId(),
            ]);

            $parent_id = $record->id;
            if ($request->hasFile('attachments')) {
                $countAtt = count($request->file('attachments'));
                for ($i = 0; $i < $countAtt; $i++) {
                    if ($request->file('attachments')[$i]->isValid()) {
                        $path = $request->file('attachments')[$i]->store('assistants/attachments/' . date('Y') . '/' . date('m'), 'public');
                        $attRecord = new Attachments;
                        $attRecord->parent_id = $parent_id;
                        $attRecord->file_name = $request->file('attachments')[$i]->getClientOriginalName();
                        $attRecord->file_size = $request->file('attachments')[$i]->getSize();
                        $attRecord->form_code = 'frm-w3';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }
            DB::commit();
            return response([
                'message' => 'Record successfully saved!',
                'id' => encode_id($record->id),
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            return response(['message' => $e], 500);
        }
    }

    protected function view($id)
    {

        $assistant = WorkshopAssistant::join('users', 'users.id', '=', 'workshop_assistants.created_by')
            ->join('provinces', 'provinces.id', '=', 'workshop_assistants.created_location')
            ->join('departments', 'departments.id', '=', 'workshop_assistants.created_department')
            // Casted joins to handle string-vs-bigint mismatch in PostgreSQL
            ->leftJoin('provinces as main_province', DB::raw('main_province.id::text'), '=', 'workshop_assistants.main_province')
            ->leftJoin('districts as main_district', DB::raw('main_district.id::text'), '=', 'workshop_assistants.main_district')
            ->leftJoin('provinces as current_province', DB::raw('current_province.id::text'), '=', 'workshop_assistants.current_province')
            ->leftJoin('districts as current_district', DB::raw('current_district.id::text'), '=', 'workshop_assistants.current_district')

            ->select(
                'workshop_assistants.*',
                'users.name as ownerName',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment',
                'main_province.name_dr as mainProvince',
                'main_district.district_dr as mainDistrict',
                'current_province.name_dr as currentProvince',
                'current_district.district_dr as currentDistrict'
            )
            ->where('workshop_assistants.id', $id)
            ->firstOrFail();
        return new AssistantResource($assistant);
    }

    protected function update(AssistantRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            $id = (int) $id;
            $assistant = WorkshopAssistant::findOrFail($id);

            $assistant->update([
                'name_dr' => $request->name_dr,
                'name_en' => $request->name_en,
                'last_name_dr' => $request->last_name_dr,
                'last_name_en' => $request->last_name_en,
                'f_name_da' => $request->f_name_da,
                'email' => $request->email,
                'phone' => $request->phone,
                'passport_no' => $request->passport_no,
                'country' => $request->country,
                'type_residence_info' => $request->type_residence_info,
                'main_province' => $request->main_province,
                'main_district' => $request->main_district,
                'main_village' => $request->main_village,
                'current_province' => $request->current_province,
                'current_district' => $request->current_district,
                'current_village' => $request->current_village,
                'company_id' => $request->company_id ? (int) decode_id($request->company_id) : null,
            ]);

            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photoPath = $request->file('photo')->store('assistant/photos/' . date('Y') . '/' . date('m'), 'public');
                $assistant->photo = asset('storage/' . $photoPath);
                $assistant->save();
            }

            if ($request->hasFile('attachments')) {
                Attachments::where('parent_id', $id)
                    ->where('form_code', 'frm-w3')
                    ->delete(); // Delete existing attachments for this record
                $countAtt = count($request->file('attachments'));
                for ($i = 0; $i < $countAtt; $i++) {
                    if ($request->file('attachments')[$i]->isValid()) {
                        $path = $request->file('attachments')[$i]->store('assistants/attachments/' . date('Y') . '/' . date('m'), 'public');
                        $attRecord = new Attachments;
                        $attRecord->parent_id = $id;
                        $attRecord->file_name = $request->file('attachments')[$i]->getClientOriginalName();
                        $attRecord->file_size = $request->file('attachments')[$i]->getSize();
                        $attRecord->form_code = 'frm-w3';
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();
            return response([
                'message' => 'Record successfully updated!',
                'id' => encode_id($assistant->id),
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response(['message' => $e], 500);
        }
    }

    protected function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:assistants,id',
            'status' => 'required|boolean',
            'reason_dismissed' => 'required|string'
        ]);

        $assistant = Assistant::find($request->input('id'));
        if (!$assistant) {
            return response()->json([
                'message' => 'Assistant not found.',
            ], 404);
        }
        $parent_id = $assistant->id;
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('assistants/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments();
                    $attRecord->parent_id = $parent_id;
                    $attRecord->file_name = $file->getClientOriginalName();
                    $attRecord->file_size = $file->getSize();
                    $attRecord->form_code = 'frm-02';
                    $attRecord->path_name = $path;
                    $attRecord->created_by = userid();
                    $attRecord->save();
                }
            }
        }

        $assistant->status = (int) $request->input('status');
        $assistant->reason_dismissed = $request->input('reason_dismissed');
        $assistant->save();
        return response()->json([
            'message' => 'Status updated successfully.',
        ], 200);
    }

    protected function createButton(Request $request)
    {
        $companyId = $request->has('id') ? decode_id($request->id) : null;
        $query = Assistant::select('assistants.status')
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('assistants.company_id', $companyId)
                    ->where('assistants.status', 1);
            });
        $records = $query->get();
        return AssistantResource::collection($records);
    }
}
