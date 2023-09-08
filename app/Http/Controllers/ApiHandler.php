<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Datasources;

class ApiHandler extends Controller {
    //


    public function __construct() {
    
    
    }



    public function getDatasourceMapData(Request $request) {
    
        $datasource_id = $request->input('ds_id');

        $datasource = Datasources::where(['id' => $datasource_id])->first();

        if(!$datasource) {

            return response()->json(array(
                'success' => false,
                'data' => null,
                'error' => 'No data source with this id exists'
            ));
        
        }


        $filePath = $datasource->file_path;

        $absolute_file_path = storage_path('app/' . $filePath);

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

    
    }



}
