<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FakeApiController;

use App\Http\Controllers\ApiHandler;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/csv-preview', function(Request $request) {

    $csv_data = $request->file('csv_file');

    if(!$csv_data) {

        return response()->json([

            "success" => false,
            "data" => []
        
        ]);
    
    }

    $csvData = array_map("str_getcsv", file($csv_data));

    $csvHeaders = $csvData[0];

    $first_10_records = array_slice($csvData, 1, 10);

    // dd($first_10_records);
    return response()->json([
        "success" => true,
        "data" => array(
           "headers" => $csvHeaders,
           "preview_rows" => $first_10_records
        )
    ]);


});

Route::post('/csv-extract', function(Request $request) {
    $file_path = $request->input('csv_file'); 

    $absolute_file_path = storage_path('app/'.$file_path);

    if (!file_exists($absolute_file_path)) {
        return response()->json([
            "success" => false,
            "data" => []
        ]);
    }

    $csvData = array_map("str_getcsv", file($absolute_file_path));
    $csvHeaders = $csvData[0];
    $first_10_records = array_slice($csvData, 1, 10);

    return response()->json([
        "success" => true,
        "data" => array(
            "headers" => $csvHeaders,
            "preview_rows" => $first_10_records
        )
    ]);


});


Route::post('/get_datasource_mapping', [ApiHandler::class, 'getDatasourceMapData']);


Route::post('/create_new_data_source', [ApiHandler::class, 'createDataSourceForUser']);

Route::get('/test_api_method', [ApiHandler::class, 'test_api_method']);


/// FAKE API ENDPOINTS START HERE


Route::get('/get_post_types', [FakeApiController::class, 'getPostTypes']);
Route::post('/get_templates_by_type', [FakeApiController::class, 'getPostsByType']);
Route::post('/get_template_vars', [FakeApiController::class, 'getTemplateVarsById']);

Route::get('/validate_auth_key', [FakeApiController::class, 'validateAuthKey']);

Route::post('/create-campaign', [ApiHandler::class, 'createCampaign']);
Route::post('/generate-key', [ApiHandler::class, 'generateKey']);
Route::post('/verify-website', [ApiHandler::class, 'verifyWebsite']);



/// FAKE API ENDOINTS END HERE