<?php

namespace App\Http\Controllers\green_zone;

use App\Http\Controllers\Controller;
use App\Models\green_zone\License;
use App\Models\green_zone\vehicle;
use App\Models\green_zone\VehicleSave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware('permission:reports')->only('index');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    protected function index(Request $request)
    {
        $startDate = null;
        $endDate   = null;
        $vehicleId = $request->input('vehicle_id');

        $startRaw = trim($request->input('start_date'));
        $endRaw   = trim($request->input('end_date'));

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

        // VEHICLE query (counts vehicles)
        $vehicleQuery = vehicle::query();
        if ($vehicleId) {
            $vehicleQuery->where('vehicle_type', $vehicleId);
        }
        if ($startDate && $endDate) {
            $vehicleQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $vehicleQuery->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $vehicleQuery->where('created_at', '<=', $endDate);
        }

        // LICENSE base query
        $licenseBase = License::query()->where('status', 2);

        if ($vehicleId) {
            $licenseBase->whereHas('vehicle', function ($q) use ($vehicleId) {
                $q->where('vehicle_type', $vehicleId);
            });
        }

        if ($startDate && $endDate) {
            $licenseBase->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $licenseBase->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $licenseBase->where('created_at', '<=', $endDate);
        }

        // Split license queries by type
        $newLicenseQuery    = (clone $licenseBase)->where('license_type', 'new');
        $extendLicenseQuery = (clone $licenseBase)->where('license_type', 'extend');
        $renewLicenseQuery  = (clone $licenseBase)->where('license_type', 'renew');


        // Counts
        $totalCompanies     = $vehicleQuery->count();
        $totalLicense       = $licenseBase->count();
        $newtotalLicense    = $newLicenseQuery->count();
        $extendtotalLicense = $extendLicenseQuery->count();
        $renewtotalLicense  = $renewLicenseQuery->count();

        $inactiveCompanies  = (clone $vehicleQuery)->where('status', 0)->count();
        $activeCompanies    = (clone $vehicleQuery)->where('status', 1)->count();

        // Active Licenses
        $activeLicenses = License::where('status', 2)->count();
        // Non- Active Licenses 
        $today = Carbon::now();

        $table = (new License)->getTable();

        // Get latest ACTIVE (status = 1) license per vehicle
        $latestLicenses = License::where('status', 2)
            ->select('vehicle_id')
            ->selectRaw('MAX(id) as latest_id')
            ->groupBy('vehicle_id');

        $latestIds = License::from($table . ' as l')
            ->joinSub($latestLicenses, 'latest', function ($join) {
                $join->on('l.id', '=', 'latest.latest_id');
            })
            ->pluck('l.id');

        License::whereIn('id', $latestIds)
            ->chunk(500, function ($licenses) use (&$nonActiveLicenses, $today) {

                foreach ($licenses as $license) {

                    $val = trim($license->expire_date ?? '');
                    if ($val === '') continue;

                    if (!preg_match('/(\d{3,4})\D+(\d{1,2})\D+(\d{1,2})/', $val, $m)) {
                        continue;
                    }

                    try {
                        $expireCarbon = Jalalian::fromFormat(
                            'Y-m-d',
                            sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3])
                        )->toCarbon()->endOfDay();

                        if ($expireCarbon->lt($today)) {
                            $nonActiveLicenses++;
                        }
                    } catch (\Exception $e) {
                        // ignore bad rows
                    }
                }
            });

        return response()->json([
            'data' => [
                'total_companies'      => $totalCompanies,
                'total_license'        => $totalLicense,
                'total_new_license'    => $newtotalLicense,
                'total_extend_license' => $extendtotalLicense,
                'total_renew_license'  => $renewtotalLicense,
                'inactive_companies'   => $inactiveCompanies,
                'active_companies'     => $activeCompanies,
                'active_licenses'      => $activeLicenses,
                'non_active_licenses'  => $nonActiveLicenses,
            ],
        ]);
    }

    protected function listCompany(Request $request)
    {
        $companies = VehicleSave::all();
        return response()->json($companies);
    }

    protected function monthlyCompanyStats()
    {
        $stats = vehicle::select(
            DB::raw('COUNT(id) as count'),
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('EXTRACT(YEAR FROM created_at) as year')
        )
            ->groupBy(DB::raw('EXTRACT(YEAR FROM created_at)'), DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->orderBy(DB::raw('EXTRACT(YEAR FROM created_at)'), 'asc')
            ->orderBy(DB::raw('EXTRACT(MONTH FROM created_at)'), 'asc')
            ->get();

        return response()->json($stats);
    }
    // This function is for local

    protected function gen_excel_report(Request $request)
    {
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

        // Accept whichever param the frontend sends; keep legacy name vehicle_id
        $rawVehicleId = $request->input('vehicle_id');

        // Defensive decode if frontend sends encoded id (non-numeric)
        $vehicleId = null;
        if (!is_null($rawVehicleId) && $rawVehicleId !== '') {
            if (!is_numeric($rawVehicleId)) {
                // If you have a decode_id helper, use it. If not, this will keep the original value.
                try {
                    $decoded = decode_id($rawVehicleId);
                    $vehicleId = is_numeric($decoded) ? (int)$decoded : $rawVehicleId;
                } catch (\Throwable $e) {
                    // fallback to raw value
                    $vehicleId = $rawVehicleId;
                }
            } else {
                $vehicleId = (int)$rawVehicleId;
            }
        }

        // Build base query
        $query = DB::table('gzlicenses')
            ->leftJoin('vehicles', 'gzlicenses.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('vehicle_saves', 'vehicles.vehicle_type', '=', 'vehicle_saves.id')
            ->leftJoin('drivers', 'gzlicenses.driver_id', '=', 'drivers.id')
            ->leftJoin('users', 'gzlicenses.created_by', '=', 'users.id')
            ->select(
                'gzlicenses.*',
                'vehicle_saves.name as vehicle_name',
                'drivers.name as driver_name',
                'users.name as ownerName'
            )
            ->where('gzlicenses.status', 2);

        if (!is_null($vehicleId) && $vehicleId !== '') {
            $existsInVehicleSaves = DB::table('vehicle_saves')->where('id', $vehicleId)->exists();
            if ($existsInVehicleSaves) {
                $query->where('vehicle_saves.id', $vehicleId);
            } else {
                $existsInVehicles = DB::table('vehicles')->where('id', $vehicleId)->exists();
                if ($existsInVehicles) {
                    $query->where('gzlicenses.vehicle_id', $vehicleId);
                } else {
                }
            }
        }

        // License type filter
        $query->when($request->input('license_type'), function ($q) use ($request) {
            $q->where('gzlicenses.license_type', $request->input('license_type'));
        });

        // Date filters
        $query->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
            $q->whereBetween('gzlicenses.created_at', [$startDate, $endDate]);
        })
            ->when($startDate && !$endDate, function ($q) use ($startDate) {
                $q->where('gzlicenses.created_at', '>=', $startDate);
            })
            ->when($endDate && !$startDate, function ($q) use ($endDate) {
                $q->where('gzlicenses.created_at', '<=', $endDate);
            });

        $records = $query->get();

        // ------------------------
        // Excel generation
        // ------------------------
        $spreadsheet = IOFactory::load(public_path('excel/templates/list_of_printed_cards.xlsx'));
        $worksheet = $spreadsheet->getActiveSheet();

        // Date range header
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

        $worksheet->mergeCells('A2:F2');
        $worksheet->setCellValue('A2', $dateRangeText);
        $worksheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $startRow = 4;
        $serial = 1;
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        if ($records->isNotEmpty()) {
            foreach ($records as $record) {
                $worksheet->setCellValue('A' . $startRow, $serial);
                $worksheet->setCellValue('B' . $startRow, $record->vehicle_name);
                $worksheet->setCellValue('C' . $startRow, $record->driver_name);

                $licenseTypeLabel = match ($record->license_type) {
                    'new' => 'جدید',
                    'renew' => 'مثنی',
                    'extend' => 'تمدید',
                    default => 'نامشخص',
                };

                $worksheet->setCellValue('D' . $startRow, $licenseTypeLabel);
                $worksheet->setCellValue('E' . $startRow, $record->issue_date);
                $worksheet->setCellValue('F' . $startRow, $record->expire_date);

                foreach (range('A', 'F') as $col) {
                    $worksheet->getStyle($col . $startRow)->applyFromArray($borderStyle);
                }

                $startRow++;
                $serial++;
            }
        } else {
            $worksheet->mergeCells('A' . $startRow . ':F' . $startRow);
            $worksheet->setCellValue('A' . $startRow, 'هیچ جواز فعالی برای این وسیله نقلیه یافت نشد.');
            $worksheet->getStyle('A' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle('A' . $startRow)->applyFromArray($borderStyle);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        if (ob_get_length()) {
            ob_end_clean();
        }

        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return response($excelOutput, 200)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="list_of_cards_sent_for_printing.xlsx"')
            ->header('Cache-Control', 'max-age=0');
    }

    // This Function is for Server 

    // protected function gen_excel_report(Request $request)
    // {
    //     $startDate = null;
    //     $endDate = null;
    //     $startRaw = trim($request->input('start_date'));
    //     $endRaw = trim($request->input('end_date'));

    //     try {
    //         if (!empty($startRaw)) {
    //             $startDate = Jalalian::fromFormat('Y/m/d', $startRaw)->toCarbon()->startOfDay();
    //         }
    //         if (!empty($endRaw)) {
    //             $endDate = Jalalian::fromFormat('Y/m/d', $endRaw)->toCarbon()->endOfDay();
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Invalid date format. Please use YYYY/MM/DD format.'], 422);
    //     }

    //     $vehicleIdRaw = $request->input('vehicle_id');
    //     $vehicleId = null;
    //     if (!is_null($vehicleIdRaw) && $vehicleIdRaw !== '') {
    //         $vehicleId = is_numeric($vehicleIdRaw) ? (int)$vehicleIdRaw : decode_id($vehicleIdRaw);
    //     }

    //     // ----------------------
    //     // Build query with BIGINT cast for PostgreSQL
    //     // ----------------------
    //     $query = DB::table('gzlicenses')
    //         ->leftJoin('vehicles', 'gzlicenses.vehicle_id', '=', 'vehicles.id')
    //         ->leftJoin('vehicle_saves', function ($join) {
    //             $join->on(DB::raw('CAST(vehicles.vehicle_type AS BIGINT)'), '=', 'vehicle_saves.id');
    //         })
    //         ->leftJoin('drivers', 'gzlicenses.driver_id', '=', 'drivers.id')
    //         ->leftJoin('users', 'gzlicenses.created_by', '=', 'users.id')
    //         ->select(
    //             'gzlicenses.*',
    //             'vehicle_saves.name as vehicle_name',
    //             'drivers.name as driver_name',
    //             'users.name as ownerName'
    //         )
    //         ->where('gzlicenses.status', 2);

    //     // ----------------------
    //     // Vehicle filter
    //     // ----------------------
    //     if ($vehicleId) {
    //         // Check if vehicleId exists in vehicle_saves (to get all vehicles of that type)
    //         $existsInVehicleSaves = DB::table('vehicle_saves')->where('id', $vehicleId)->exists();
    //         if ($existsInVehicleSaves) {
    //             $query->where(DB::raw('CAST(vehicles.vehicle_type AS BIGINT)'), '=', $vehicleId);
    //         } else {
    //             // fallback: if vehicleId exists in vehicles
    //             $existsInVehicles = DB::table('vehicles')->where('id', $vehicleId)->exists();
    //             if ($existsInVehicles) {
    //                 $query->where('gzlicenses.vehicle_id', $vehicleId);
    //             }
    //         }
    //     }

    //     // License type filter
    //     if ($request->input('license_type')) {
    //         $query->where('gzlicenses.license_type', $request->input('license_type'));
    //     }

    //     // Date filters
    //     if ($startDate && $endDate) {
    //         $query->whereBetween('gzlicenses.created_at', [$startDate, $endDate]);
    //     } elseif ($startDate) {
    //         $query->where('gzlicenses.created_at', '>=', $startDate);
    //     } elseif ($endDate) {
    //         $query->where('gzlicenses.created_at', '<=', $endDate);
    //     }

    //     $records = $query->get();

    //     // ----------------------
    //     // Excel generation (unchanged)
    //     // ----------------------
    //     $spreadsheet = IOFactory::load(public_path('excel/templates/list_of_printed_cards.xlsx'));
    //     $worksheet = $spreadsheet->getActiveSheet();

    //     // Date range header
    //     $dateRangeText = 'راپور از تاریخ: ';
    //     if ($startDate && $endDate) {
    //         $startJalali = Jalalian::fromCarbon($startDate)->format('Y/m/d');
    //         $endJalali = Jalalian::fromCarbon($endDate)->format('Y/m/d');
    //         $dateRangeText .= $startJalali . ' تا ' . $endJalali;
    //     } elseif ($startDate) {
    //         $startJalali = Jalalian::fromCarbon($startDate)->format('Y/m/d');
    //         $dateRangeText .= 'از ' . $startJalali;
    //     } elseif ($endDate) {
    //         $endJalali = Jalalian::fromCarbon($endDate)->format('Y/m/d');
    //         $dateRangeText .= 'تا ' . $endJalali;
    //     } else {
    //         $dateRangeText .= 'تمام تاریخ ها';
    //     }

    //     $worksheet->mergeCells('A2:F2');
    //     $worksheet->setCellValue('A2', $dateRangeText);
    //     $worksheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    //     $startRow = 4;
    //     $serial = 1;
    //     $borderStyle = [
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => 'FF000000'],
    //             ],
    //         ],
    //     ];

    //     if ($records->isNotEmpty()) {
    //         foreach ($records as $record) {
    //             $worksheet->setCellValue('A' . $startRow, $serial);
    //             $worksheet->setCellValue('B' . $startRow, $record->vehicle_name);
    //             $worksheet->setCellValue('C' . $startRow, $record->driver_name);

    //             $licenseTypeLabel = match ($record->license_type) {
    //                 'new' => 'جدید',
    //                 'renew' => 'مثنی',
    //                 'extend' => 'تمدید',
    //                 default => 'نامشخص',
    //             };

    //             $worksheet->setCellValue('D' . $startRow, $licenseTypeLabel);
    //             $worksheet->setCellValue('E' . $startRow, $record->issue_date);
    //             $worksheet->setCellValue('F' . $startRow, $record->expire_date);

    //             foreach (range('A', 'F') as $col) {
    //                 $worksheet->getStyle($col . $startRow)->applyFromArray($borderStyle);
    //             }

    //             $startRow++;
    //             $serial++;
    //         }
    //     } else {
    //         $worksheet->mergeCells('A' . $startRow . ':F' . $startRow);
    //         $worksheet->setCellValue('A' . $startRow, 'هیچ جواز فعالی برای این وسیله نقلیه یافت نشد.');
    //         $worksheet->getStyle('A' . $startRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //         $worksheet->getStyle('A' . $startRow)->applyFromArray($borderStyle);
    //     }

    //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

    //     if (ob_get_length()) {
    //         ob_end_clean();
    //     }

    //     ob_start();
    //     $writer->save('php://output');
    //     $excelOutput = ob_get_clean();

    //     return response($excelOutput, 200)
    //         ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
    //         ->header('Content-Disposition', 'attachment; filename="list_of_cards_sent_for_printing.xlsx"')
    //         ->header('Cache-Control', 'max-age=0');
    // }
}
