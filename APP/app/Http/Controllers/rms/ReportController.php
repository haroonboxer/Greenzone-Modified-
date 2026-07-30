<?php

namespace App\Http\Controllers\rms;

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


        $applyFilters = function ($query) use ($companyId, $startDate, $endDate) {
            if ($companyId) {
                $query->where('id', $companyId);
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
        $companyQuery = Company::query();
        $applyFilters($companyQuery);

        // Fetching contracts
        $contractQuery = Contract::query();
        $applyFilters($contractQuery);

        // Fetching bosses
        $bossQuery = Boss::query();
        $applyFilters($bossQuery);

        // Fetching assistants
        $assistantQuery = Assistant::query();
        $applyFilters($assistantQuery);

        // Fetching licenses
        $licenseQuery = License::query();
        $applyFilters($licenseQuery);

        // Fetching employees
        $empQuery = Employee::query();
        $applyFilters($empQuery);

        // Fetching weapons tables
        $tableQuery = WeaponsGeneralTable::query();
        $applyFilters($tableQuery);

        // Fetching guns
        $gunQuery = Gun::query();
        $applyFilters($gunQuery);

        // Fetching vehicles
        $vehQuery = Vehical::query();
        $applyFilters($vehQuery);

        // Fetching printed cards
        $printedQuery = PrintedCard::query();
        $applyFilters($printedQuery);

        // Counting companies
        $totalCompanies = $companyQuery->count();
        $inactiveCompanies = (clone $companyQuery)->where('status', 0)->count();
        $activeCompanies = (clone $companyQuery)->where('status', 1)->count();

        // Counting printed cards
        $totalPrintedCard = $printedQuery->count();
        $inactivePrintedCard = (clone $printedQuery)->where('status', 0)->count();
        $activePrintedCard = (clone $printedQuery)->where('status', 1)->count();
        $newPrintedCard = (clone $printedQuery)->where('card_type', 'new')->count();
        $extendPrintedCard = (clone $printedQuery)->where('card_type', 'extend')->count();

        // Counting weapons tables
        $totalTable = $tableQuery->count();
        $inactiveTable = (clone $tableQuery)->where('status', 0)->count();
        $activeTable = (clone $tableQuery)->where('status', 1)->count();

        $totalGun = $gunQuery->count();
        $totalVec = $vehQuery->count();

        // Counting bosses
        $totalBoss = $bossQuery->count();
        $inactiveBoss = (clone $bossQuery)->where('status', 0)->count();
        $activeBoss = (clone $bossQuery)->where('status', 1)->count();

        // Counting assistants
        $totalAssistant = $assistantQuery->count();
        $inactiveAssistant = (clone $assistantQuery)->where('status', 0)->count();
        $activeAssistant = (clone $assistantQuery)->where('status', 1)->count();

        // Counting employees
        $totalEmp = $empQuery->count();
        $inactiveEmp = (clone $empQuery)->where('status', 0)->count();
        $activeEmp = (clone $empQuery)->where('status', 1)->count();

        // Counting contracts
        $totalContracts = $contractQuery->count();
        $inactiveContracts = (clone $contractQuery)->where('status', 0)->count();
        $activeContracts = (clone $contractQuery)->where('status', 1)->count();
        $cancleContracts = (clone $contractQuery)->where('status', 4)->count();
        $expiredContracts = (clone $contractQuery)->where('status', 2)->count();

        // Counting licenses
        $totalLicense = $licenseQuery->count();
        $inactiveLicense = (clone $licenseQuery)->where('status', 0)->count();
        $activeLicense = (clone $licenseQuery)->where('status', 1)->count();
        $cancleLicense = (clone $licenseQuery)->where('status', 4)->count();
        $expiredLicense = (clone $licenseQuery)->where('status', 2)->count();
        $newLicense = (clone $licenseQuery)->where('license_type', 'new')->count();
        $extendLicense = (clone $licenseQuery)->where('license_type', 'extend')->count();
        $renewLicense = (clone $licenseQuery)->where('license_type', 'renew')->count();

        return response()->json([
            'data' => [
                'total_companies' => $totalCompanies,
                'inactive_companies' => $inactiveCompanies,
                'active_companies' => $activeCompanies,
                'total_boss' => $totalBoss,
                'active_boss' => $activeBoss,
                'inactive_boss' => $inactiveBoss,
                'total_assistant' => $totalAssistant,
                'inactive_assistant' => $inactiveAssistant,
                'active_assistant' => $activeAssistant,
                'total_contracts' => $totalContracts,
                'active_contracts' => $activeContracts,
                'inactive_contracts' => $inactiveContracts,
                'expired_contracts' => $expiredContracts,
                'cancle_contracts' => $cancleContracts,
                'total_license' => $totalLicense,
                'inactive_license' => $inactiveLicense,
                'active_license' => $activeLicense,
                'expired_license' => $expiredLicense,
                'cancle_license' => $cancleLicense,
                'new_license' => $newLicense,
                'extend_license' => $extendLicense,
                'renew_license' => $renewLicense,
                'total_emp' => $totalEmp,
                'inactive_emp' => $inactiveEmp,
                'active_emp' => $activeEmp,
                'total_gun' => $totalGun,
                'total_table' => $totalTable,
                'inactive_table' => $inactiveTable,
                'active_table' => $activeTable,
                'total_veh' => $totalVec,
                'total_cards' => $totalPrintedCard,
                'active_cards' => $activePrintedCard,
                'inactive_cards' => $inactivePrintedCard,
                'new_cards' => $newPrintedCard,
                'extend_cards' => $extendPrintedCard
            ],
        ]);
    }

    protected function listCompany(Request $request)
    {
        $companies = Company::select('id', 'company_dr')
            ->orderBy('company_dr')
            ->get();

        return response()->json($companies);
    }


    protected function monthlyCompanyStats()
    {
        $stats = Company::select(
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
}
