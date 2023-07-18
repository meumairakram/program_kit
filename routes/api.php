<?php

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