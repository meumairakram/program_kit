<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Datasources;

use Illuminate\Support\Facades\Auth;


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


    function createDataSourceForUser(Request $request) {

        

        $title = $request->input('title') != '' ? $request->input('title') : null;
        $type = $request->input('type');
        $requires_mapping = $request->input('requires_mapping') ;

        $file_path = $request->input('file_path');
        $sheet_id = $request->input('sheet_id');

  
        $title = $type == 'google_sheet' ? 'G-Sheet: ' . $title : $title;  

        

        $newDatasource = new Datasources();
        $newDatasource->type = $type;
        $newDatasource->name = $title;
        $newDatasource->requires_mapping = $requires_mapping;
        $newDatasource->owner_id = 2;


        if($type == 'csv') {

            $newDatasource->file_path = $file_path;
            $newDatasource->records_count = 100;
            $newDatasource->last_synced = date('Y-m-d H:m:i',time());

        } elseif ($type == 'google_sheet') {
    
            $newDatasource->sheet_id = $sheet_id;    
            $newDatasource->records_count = 0;
            $newDatasource->last_synced = date('Y-m-d H:m:i',time());
            $newDatasource->file_path = 'none';
        }

      
        $newDatasource->save();


        return response()->json([
            'success' => true,
            'data' => [
                'id' => $newDatasource->id,
                'title' => $title
            ],
            'error' => ''
        ]);


    
    }



}
