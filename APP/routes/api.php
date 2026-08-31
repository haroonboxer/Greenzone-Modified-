    <?php

    use App\Http\Controllers\Auth\AuthController;
    use App\Http\Controllers\Auth\DirectorateController;
    use App\Http\Controllers\green_zone\CardController;
    use App\Http\Controllers\rms\PrintedCardController;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Http\Request;
    /*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



    Route::get('printed_card/view/{id}', [PrintedCardController::class, 'generateIDCard']);
    Route::get('/card/print/{id}', [CardController::class, 'generateLicense'])->name('card.print');
    Route::middleware('web')->get('JumpToOtherProject/{id}',[AuthController::class, 'JumpToOtherProject']
);







    Route::middleware('auth:sso')->group(function () {
        require('user_routes.php');
        require('administration.php');
        require('company_routes.php');
        require('boss_routes.php');
        require('assistant_route.php');
        require('employee_route.php');
        require('weapon.php');
        require('license_route.php');
        require('gun_route.php');
        require('contracts_route.php');
        require('vehicals_route.php');
        require('printed_card_route.php');
        require('reports_routes.php');
        require('workshopCompany_routes.php');
        require('workshopAssistant_routes.php');
        require('workshop_boss_routes.php');
        require('workshop_license_route.php');
        //  require('workshop_report_route.php');
        require('card_print_route.php');
        require('vehicle_route.php');
        require('driver_route.php');
        require('gz_license_route.php');
        require('card_route.php');
        require('vehicle_save_route.php');
    });
