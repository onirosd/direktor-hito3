<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RestrictionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/survey', \App\Http\Controllers\SurveyController::class);

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);
    Route::post('/info_person', [AuthController::class, 'info_person']);
    Route::post('/upd_person', [AuthController::class, 'upd_person']);

});

Route::get('/survey-by-slug/{survey:slug}', [\App\Http\Controllers\SurveyController::class, 'showForGuest']);
Route::post('/survey/{survey}/answer', [\App\Http\Controllers\SurveyController::class, 'storeAnswer']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/create_project', [ProjectController::class, 'create_project']);
Route::post('/get_project', [ProjectController::class, 'get_project']);
Route::post('/edit_project', [ProjectController::class, 'edit_project']);
Route::post('/get_projectreport', [ProjectController::class, 'get_projectreport']);
Route::post('/get_projectuser', [ProjectController::class, 'get_projectuser']);
Route::post('/update_member', [RestrictionController::class, 'update_member']);
Route::post('/add_front', [RestrictionController::class, 'add_front']);
Route::post('/add_phase', [RestrictionController::class, 'add_phase']);
Route::post('/add_Actividad', [RestrictionController::class, 'add_Actividad']);
Route::post('/get_front', [RestrictionController::class, 'get_front']);
Route::get('/get_restriction_p', [RestrictionController::class, 'get_restriction_p']);
Route::post('/add_restriction',[RestrictionController::class, 'add_restriction']);
Route::post('/delete_front',[RestrictionController::class, 'delete_front']);
Route::post('/delete_restriction',[RestrictionController::class, 'delete_restriction']);
Route::post('/duplicate_restriction',[RestrictionController::class, 'duplicate_restriction']);


Route::get('/get_cargos', [\App\Http\Controllers\UtilsController::class, 'get_cargos']);
Route::get('/get_tipoproyectos', [\App\Http\Controllers\UtilsController::class, 'get_tipoproyectos']);
Route::get('/get_ubigeo', [\App\Http\Controllers\UtilsController::class, 'get_ubigeo']);
Route::get('/get_moneda', [\App\Http\Controllers\UtilsController::class, 'get_moneda']);
Route::get('/get_areaintegrante', [\App\Http\Controllers\UtilsController::class, 'get_areaintegrante']);
Route::get('/get_proyrolintegrante', [\App\Http\Controllers\UtilsController::class, 'get_proyrolintegrante']);
Route::get('/get_utilitarios_proyecto', [\App\Http\Controllers\UtilsController::class, 'get_utilitarios_proyecto']);


Route::post('/get_buscar', [\App\Http\Controllers\UtilsController::class, 'get_search_empresa']);
Route::post('/set_new_empresa', [\App\Http\Controllers\UtilsController::class, 'set_new_empresa']);
Route::post('/get_data_restricciones', [RestrictionController::class, 'get_data_restricciones']);
Route::post('/upd_restricciones', [RestrictionController::class, 'upd_restricciones']);


Route::post('/get_restrictionsMember', [RestrictionController::class, 'get_restrictionsMember']);
Route::post('/get_estado', [RestrictionController::class, 'get_estado']);



