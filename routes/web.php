<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;

use App\Http\Controllers\SessionsController;
use App\Http\Controllers\DatasourcesController;
use App\Http\Controllers\GoogleSheetController;
use App\Http\Controllers\ChangePasswordController;

use App\Http\Controllers\FakeApiController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home'])
	;
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');


	
	// Google Sheets
	Route::get('sheets', [GoogleSheetController::class, 'sheets'])->name('sheets');
	Route::post('store-sheet-data', [GoogleSheetController::class, 'storeSheetData'])->name('store-sheet-data');
	
	Route::get('sheets/init', [App\Http\Controllers\SheetsController::class, 'init'])->name('sheets.init');
	Route::post('sheets/create_new', [App\Http\Controllers\SheetsController::class, 'http_create_new_sheet'])->name('sheets.create_new');
	Route::post('sheets/get_sheet_row', [App\Http\Controllers\SheetsController::class, 'http_get_sheet_row'])->name('sheets.get_sheet_row');
	Route::get('sheets/test', [App\Http\Controllers\SheetsController::class, 'testRoute'])->name('sheets.test');
	Route::post('sheets/listen/changes', [App\Http\Controllers\SheetsController::class, 'listenChanges'])->name('sheets.listenChanges');


	// Camapaign Management
	Route::get('campaign-management', [CampaignController::class, 'manage'])->name('campaign-management');
	Route::post("create-campaign", [CampaignController::class, 'store'])->name("store-campaign");
	Route::get('create-campaign', [CampaignController::class, 'create'])->name('create-campaign');
	Route::get('edit-campaign/{id}', [CampaignController::class, 'edit'])->name('edit-campaign');
	Route::post('edit-campaign', [CampaignController::class, 'update'])->name('update-campaign');
	Route::get('delete-campaign/{id}', [CampaignController::class, 'delete'])->name('delete-campaign');
	Route::get('campaign-status/{camp_id}', [CampaignController::class, 'campaignStatusPage'])->name('campaign-status');

	Route::get('data_api/campaign_info/{camp_id}',[CampaignController::class, 'data_api_campaign_info'])->name('camapaign-info-datapi');
	// Route::get('data_api/get_status/{title}',[CampaignController::class, 'data_api_get_status'])->name('get_status-datapi');
	Route::get('data_api/start_campaign/{camp_id}',[CampaignController::class, 'data_api_campaign_start'])->name('camapaign-start-datapi');
	Route::get('data_api/ping_website/{camp_id}',[CampaignController::class, 'data_api_campaign_ping'])->name('camapaign-ping-datapi');
	Route::get('data_api/reset_campaign_status/{camp_id}', [CampaignController::class , 'data_api_reset_campaign_status'])->name('campaign_reset_status');


    Route::post('/websites_type', [CampaignController::class, 'selectWebSite']);

	// websites
	Route::get('website-management', [WebsiteController::class, 'manage'])->name('website-management');
	Route::get('add-website', [WebsiteController::class, 'add_new'])->name('add-website');
	Route::post('add-website', [WebsiteController::class, 'store'])->name('store-add-website');
	Route::get('edit-website/{id}', [WebsiteController::class, 'edit'])->name('edit-website');
	Route::post('edit-website', [WebsiteController::class, 'update'])->name('update-website');
	Route::get('delete-website/{id}', [WebsiteController::class, 'delete'])->name('delete-website');


	// Data sources
	Route::get('manage-datasources', [DatasourcesController::class, 'manage'])->name('manage-datasources');
	Route::get('add-datasource', [DatasourcesController::class, 'add_new'])->name('add-datasource');
	Route::post('add-datasource', [DatasourcesController::class, 'store'])->name('store-datasource');
	Route::get('edit-datasource/{id}', [DatasourcesController::class, 'edit'])->name('edit-datasource');
	Route::post('edit-datasource', [DatasourcesController::class, 'update'])->name('update-datasource');
	Route::get('delete-datasource/{id}', [DatasourcesController::class, 'delete'])->name('delete-datasource');

	
	Route::get('/test_endpoint', [FakeApiController::class, 'test_endpoint']);



	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

    Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});



Route::get('/login', function () {
    return view('session/login-session');
})->name('login');