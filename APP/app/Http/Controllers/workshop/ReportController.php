<?php

namespace App\Http\Controllers\workshop;

use App\Http\Controllers\Controller;
use App\Models\rms\Company;
use App\Models\rms\Contract;
use App\Models\rms\Boss;
use App\Models\rms\Assistant;
use App\Models\rms\License;
use App\Models\rms\Employee;
use App\Models\rms\WeaponsGeneralTable;
use App\Models\rms\Gun;
use App\Models\rms\Vehical;
use App\Models\rms\PrintedCard;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\workshop\workshopCompany;
use App\Models\workshop\WorkshopLicense;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:system-report')->only('index');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected function index(Request $request)
    {
        $startDate = null;
        $endDate = null;
        $companyId = $request->input('company_id');

        $startRaw = trim($request->input('start_date'));
        $endRaw = trim($request->input('end_date'));

        try {
            if (!empty($startRaw)) {
                $startDate = Jalalian::fromFormat('Y/m/d', $startRaw)->toCarbon()->startOfDay();
            }

            if (!empty($endRaw)) {
                $endDate = Jalalian::fromFormat('Y/m/d', $endRaw)->toCarbon()->endOfDay();
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format. Please use YYYY/MM/DD format.'], 422);
        }


        $applyFilters = function ($query, $type) use ($companyId, $startDate, $endDate) {
            if ($companyId && $type == 'company') {
                $query->where('id', $companyId);
            } else if ($companyId && $type != 'company') {
                $query->where('company_id', $companyId);
            }

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('created_at', '>=', $startDate);
            } elseif ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }
        };

        // Fetching companies
        $companyQuery = workshopCompany::query();
        $applyFilters($companyQuery, 'company');

        // Fetching licenses
        $licenseQuery = WorkshopLicense::query();
        $applyFilters($licenseQuery, 'license');


        // Counting companies
        $totalCompanies = $companyQuery->count();


        // Counting licenses
        $activeLicense = (clone $licenseQuery)->where('status', 1)->count();
        $cancleLicense = (clone $licenseQuery)->where('status', 4)->count();

        $newLicense = (clone $licenseQuery)->where('license_type', 'new')->where('status', 2)->count();
        $extendLicense = (clone $licenseQuery)->where('license_type', 'extend')->where('status', 2)->count();
        $renewLicense = (clone $licenseQuery)->where('license_type', 'renew')->where('status', 2)->count();
        $printedCard = (clone $licenseQuery)->where('printed', '1')->where('status', 2)->count();
        $totalLicense = $licenseQuery->where('status', 2)->count();

        return response()->json([
            'data' => [
                'total_companies' => $totalCompanies,
                'total_license' => $totalLicense,
                'active_license' => $activeLicense,
                'cancle_license' => $cancleLicense,
                'new_license' => $newLicense,
                'extend_license' => $extendLicense,
                'renew_license' => $renewLicense,
                'printedCard' => $printedCard,
            ],
        ]);
    }

    protected function listCompany(Request $request)
    {
        $companies = workshopCompany::select('id', 'company_dr')
            ->orderBy('company_dr')
            ->get();

        return response()->json($companies);
    }

    protected function gen_excel_report(Request $request)
    {
        $sortFieldInput = $request->input('sort_field', 'id');
        $sortFields = ['id', 'created_at', 'company_id'];
        $sortField = in_array($sortFieldInput, $sortFields) ? $sortFieldInput : 'id';
        $sortOrder = $request->input('sort_order', 'desc');
        $companyId = $request->input('company_id');

        $startDate = null;
        $endDate = null;
        $startRaw = trim($request->input('start_date'));
        $endRaw = trim($request->input('end_date'));

        try {
            if (!empty($startRaw)) {
                $startDate = Jalalian::fromFormat('Y/m/d', $startRaw)->toCarbon()->startOfDay();
            }

            if (!empty($endRaw)) {
                $endDate = Jalalian::fromFormat('Y/m/d', $endRaw)->toCarbon()->endOfDay();
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format. Please use YYYY/MM/DD format.'], 422);
        }

        $query = DB::table('workshop_licenses')
            ->leftJoin('workshop_companies', 'workshop_licenses.company_id', '=', 'workshop_companies.id')
            ->leftJoin('workshop_bosses', 'workshop_bosses.company_id', '=', 'workshop_companies.id')
            ->leftJoin('workshop_assistants', 'workshop_assistants.company_id', '=', 'workshop_companies.id')
            ->leftJoin('users', 'workshop_licenses.created_by', '=', 'users.id')
            ->select(
                'workshop_licenses.*',
                'workshop_licenses.license_type',
                'workshop_companies.company_dr as company_name_dr',
                'workshop_bosses.name_dr as boss_name_dr',
                'workshop_assistants.name_dr as assistant_name_dr',
                'users.name as ownerName',
                'workshop_licenses.created_at'
            )
            ->where('workshop_licenses.status', 2)
            ->orderBy($sortField, $sortOrder)
            ->when($companyId, fn($q) => $q->where('workshop_licenses.company_id', $companyId))
            ->when($request->input('license_type'), fn($q) => $q->where('workshop_licenses.license_type', $request->input('license_type')))
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('workshop_licenses.created_at', [$startDate, $endDate]);
            })
            ->when($startDate && !$endDate, function ($q) use ($startDate) {
                $q->where('workshop_licenses.created_at', '>=', $startDate);
            })
            ->when($endDate && !$startDate, function ($q) use ($endDate) {
                $q->where('workshop_licenses.created_at', '<=', $endDate);
            });

        $records = $query->get();
        $spreadsheet = IOFactory::load(public_path('excel/templates/list_of_printed_cards.xlsx'));
        $worksheet = $spreadsheet->getActiveSheet();

        $dateRangeText = 'راپور از تاریخ: ';
        if ($startDate && $endDate) {
            $startJalali = Jalalian::fromCarbon($startDate)->format('Y/m/d');
            $endJalali = Jalalian::fromCarbon($endDate)->format('Y/m/d');
            $dateRangeText .= $startJalali . ' تا ' . $endJalali;
        } elseif ($startDate) {
            $startJalali = Jalalian::fromCarbon($startDate)->format('Y/m/d');
            $dateRangeText .= 'از ' . $startJalali;
        } elseif ($endDate) {
            $endJalali = Jalalian::fromCarbon($endDate)->format('Y/m/d');
            $dateRangeText .= 'تا ' . $endJalali;
        } else {
            $dateRangeText .= 'تمام تاریخ ها';
        }

        $worksheet->mergeCells('A2:H2');
        $worksheet->setCellValue('A2', $dateRangeText);
        $worksheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $startRow = 4;
        $firstFeeRow = $startRow;
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        foreach ($records as $record) {
            $worksheet->setCellValue('A' . $startRow, $record->id);
            $worksheet->setCellValue('B' . $startRow, $record->company_name_dr);
            $worksheet->setCellValue('C' . $startRow, $record->boss_name_dr);
            $worksheet->setCellValue('D' . $startRow, $record->assistant_name_dr);

            $licenseTypeLabel = match ($record->license_type) {
                'new' => 'جدید',
                'renew' => 'مثنی',
                'extend' => 'تمدید',
                default => 'نامشخص',
            };

            $worksheet->setCellValue('E' . $startRow, $licenseTypeLabel);
            $worksheet->setCellValue('F' . $startRow, $record->fee);
            $worksheet->setCellValue('G' . $startRow, $record->issue_date);
            $worksheet->setCellValue('H' . $startRow, $record->validity_date);

            foreach (range('A', 'H') as $col) {
                $worksheet->getStyle($col . $startRow)->applyFromArray($borderStyle);
            }

            $startRow++;
        }

        $lastFeeRow = $startRow - 1;
        $sumRow = $startRow;

        // Merge and write "مقدار عواید حاصل شده" in A to E
        $worksheet->mergeCells("A$sumRow:E$sumRow");
        $worksheet->setCellValue("A$sumRow", 'مقدار عواید حاصل شده');

        // Merge and write SUM in F to H
        $worksheet->mergeCells("F$sumRow:H$sumRow");
        $worksheet->setCellValue("F$sumRow", "=SUM(F$firstFeeRow:F$lastFeeRow)");

        // Style for yellow background and border
        $sumRowStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Apply style to merged A–E
        foreach (range('A', 'E') as $col) {
            $worksheet->getStyle($col . $sumRow)->applyFromArray($sumRowStyle);
            $worksheet->getStyle("A$sumRow:H$sumRow")->getFont()->setBold(true)->setSize(16);
        }

        // Apply style to merged F–H
        foreach (range('F', 'H') as $col) {
            $worksheet->getStyle($col . $sumRow)->applyFromArray($sumRowStyle);
            $worksheet->getStyle("A$sumRow:H$sumRow")->getFont()->setBold(true)->setSize(16);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return response($excelOutput, 200)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="list_of_cards_sent_for_printing.xlsx"')
            ->header('Cache-Control', 'max-age=0');
    }
}
