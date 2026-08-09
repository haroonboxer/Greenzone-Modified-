<?php

namespace App\Http\Controllers\rms;

use App\Http\Controllers\Controller;
use App\Http\Resources\rms\PrintedCardResource;
use App\Models\rms\PrintedCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\Attachments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Milon\Barcode\DNS2D;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrintedCardController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware('permission:printed-card-list')->only('index');
        $this->middleware('permission:printed-card-create')->only('store');
        $this->middleware('permission:printed-card-view')->only('view');
        $this->middleware('permission:printed-card-edit')->only('update');
        $this->middleware('permission:printed-card-status')->only('changeStatus');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected array $sortFields = [
        'printed_cards.id',
        'printed_cards.weapons',
        'printed_cards.card_type',
        'printed_cards.project_name_en',
        'printed_cards.issued_date',
        'printed_cards.created_at',
    ];

    protected function index(Request $request)
    {
  
        $sortFieldInput = $request->input('sort_field', self::DEFAULT_SORT_FIELD);
        $sortField = in_array($sortFieldInput, $this->sortFields) ? $sortFieldInput : self::DEFAULT_SORT_FIELD;
        $sortOrder = $request->input('sort_order', self::DEFAULT_SORT_ORDER);
        $companyId = $request->has('company_id') ? decode_id($request->company_id) : null;

        $query = PrintedCard::join('users', 'users.id', 'printed_cards.created_by')
            ->select(
                'printed_cards.*',
                'printed_cards.created_at',
                'users.name as ownerName'
            )
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('printed_cards.company_id', $companyId);
            })
            ->when($request->weapons != '', function ($query) use ($request) {
                return $query->where('printed_cards.weapons', 'LIKE', '%' . trim($request->weapons) . '%');
            })
            ->when($request->card_type != '', function ($query) use ($request) {
                return $query->where('printed_cards.card_type', 'LIKE', '%' . trim($request->card_type) . '%');
            })
            ->when($request->project_name_dr != '', function ($query) use ($request) {
                return $query->where('printed_cards.project_name_dr', 'LIKE', '%' . trim($request->project_name_dr) . '%');
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
        $record = new PrintedCard;
        $record->weapons = $request->weapons;
        $record->card_type = $request->card_type;
        $record->project_name_dr = $request->project_name_dr;
        $record->project_name_en = $request->project_name_en;
        $record->card_perimeter_dr = $request->card_perimeter_dr;
        $record->card_perimeter_en = $request->card_perimeter_en;
        $record->issued_date = $request->issued_date;
        $record->expire_date = $request->expire_date;
        $record->company_id = (int) $company_id;
        $record->created_by = userid();
        $record->created_department = departmentId();
        $record->created_location = locationId();
        $record->save();

        $parent_id = $record->id;

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment->isValid()) {
                    $path = $attachment->store('printed_card/attachments/' . date('Y') . '/' . date('m'), 'public');

                    $attRecord = new Attachments();
                    $attRecord->parent_id = $parent_id;
                    $attRecord->file_name = $attachment->getClientOriginalName();
                    $attRecord->file_size = $attachment->getSize();
                    $attRecord->form_code = 'frm-09';
                    $attRecord->path_name = $path;
                    $attRecord->created_by = userid();
                    $attRecord->save();
                }
            }
        }

        return response([
            'message' => 'Printed Card record successfully saved!',
            'id' => encode_id($parent_id),
        ], 200);
    }

    protected function view($id)
    {
        
        $printedCard = PrintedCard::join('users', 'users.id', '=', 'printed_cards.created_by')
            ->join('provinces', 'provinces.id', '=', 'printed_cards.created_location')
            ->join('departments', 'departments.id', '=', 'printed_cards.created_department')
            ->select(
                'printed_cards.*',
                'users.name as ownerName',
                'provinces.name_dr as createdLocation',
                'departments.name_da as createdDepartment'
            )
            ->where('printed_cards.id', $id)
            ->firstOrFail();

        return new PrintedCardResource($printedCard);
    }

    protected function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = (int) $id;
            $printedCard = PrintedCard::findOrFail($id);

            $printedCard->update([
                'weapons' => $request->weapons,
                'card_type' => $request->card_type,
                'project_name_dr' => $request->project_name_dr,
                'project_name_en' => $request->project_name_en,
                'card_perimeter_dr' => $request->card_perimeter_dr,
                'card_perimeter_en' => $request->card_perimeter_en,
                'issued_date' => $request->issued_date,
                'expire_date' => $request->expire_date,
                'company_id' => $request->company_id ? (int) decode_id($request->company_id) : null,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store(
                            'printed_card/attachments/' . date('Y') . '/' . date('m'),
                            'public'
                        );

                        $attRecord = new Attachments;
                        $attRecord->parent_id = $id;
                        $attRecord->file_name = $file->getClientOriginalName();
                        $attRecord->file_size = $file->getSize();
                        $attRecord->form_code = 'frm-09'; // Form Code only for Printed Cards form ...   --- 09 ---
                        $attRecord->path_name = $path;
                        $attRecord->created_by = userid();
                        $attRecord->save();
                    }
                }
            }

            DB::commit();
            return response([
                'message' => 'Printed Card record successfully updated!',
                'id' => encode_id($printedCard->id),
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response([
                'message' => 'An error occurred while updating the record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:printed_cards,id',
            'status' => 'required|in:0,1',
        ]);

        $printedCard = PrintedCard::find($request->input('id'));

        if (!$printedCard) {
            return response()->json([
                'message' => 'Printed card not found.',
            ], 404);
        }

        $printedCard->status = (int)$request->input('status');
        $printedCard->save();

        return response()->json([
            'message' => 'Printed card status updated successfully.',
        ], 200);
    }




    protected function generateIDCard($id)
    {

        $data = PrintedCard::join('companies', 'companies.id', '=', 'printed_cards.company_id')
            ->where('printed_cards.id', $id)
            ->select(
                'printed_cards.*',
                'companies.icon',
                'companies.company_dr as companyNameDr',
                'companies.company_en as companyNameEn'
            )
            ->first();

        if (!$data) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $qrContent = 'RMS-2025-000' . $data->id . "\n" . $data->companyNameEn;

        $barcodeGenerator = new DNS2D();
        $barcodeGenerator->setStorPath(storage_path('framework/barcodes'));

        $encodedContent = mb_convert_encoding($qrContent, 'UTF-8', 'auto');
        $barcode = $barcodeGenerator->getBarcodePNG($encodedContent, 'QRCODE');
        $barcodeDataUri = 'data:image/png;base64,' . $barcode;


        $html = View::make('idcard', compact('data', 'barcodeDataUri'))->render();

        return response()->json([
            'html' => $html,
        ]);
    }


    public function gen_excel_report($id = 0, Request $request)
    {
        return 'Excel Report Generation is not implemented yet.';
    }
}
