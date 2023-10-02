<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datasources;
use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;
use App\Models\CampMap;
use App\Models\WebsitesInfo;
use App\HelperClasses\WebsiteHelpers;
use App\Models\CampExecLog;
use App\Jobs\CreateTemplateOnWebsite;
use App\Models\User;


use App\HelperClasses\GoogleSheetHelpers;



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



    public function test_api_method(Request $request) {

        
        $user = User::where(['id' => 2])->first();    // temp set user
        // var_dump();
        $sheetsHelper = new GoogleSheetHelpers($user);

        // var_dump($sheetsHelper->user);

        $data = $sheetsHelper->ReadAllDataFromSheet("1l_hs1QcqCvNnQUBs72ik3kFIKJEey7gkKp-M-EaDcrI");

        if(!$data || !is_array($data) || count($data) < 1) {

            echo "Sheet is empty";
            die();
        }

        $headers = $data[0];   // consider first row as header

        $content_data = array_slice($data, 1, count($data));

        // var_dump("Headers are:");

        // var_dump($headers);


        $clean_data = [];
        $clean_headers = [];
        $plain_headers = [];
        
        foreach($headers as $index => $header_name) {
            if($header_name !== "") {
                $clean_headers[] = ['index' => $index, "header" => $header_name];                   
                $plain_headers[] = $header_name;
            }
        }   

        foreach($content_data as $content_row) {

            $current_row = [];

            foreach($clean_headers as $header_data) {
                
                $target_index = $header_data['index'];
                $header_name = $header_data['header'];

                $current_row[] = array_key_exists($target_index, $content_row) ? $content_row[$target_index] : "";
            
            }

            $clean_data[] = $current_row;
            
        
        }




        // need to split job data in sets of 20 for each job dispatch



        $job_data = array(
            'source_headers' => $plain_headers,
            'rows' => $clean_data
        );

        CreateTemplateOnWebsite::dispatch(35, $job_data);

        var_dump("Scheduled for " . count($clean_data) . " Templates");

        die();



        //  [ array(2) { ["index"]=> int(0) ["header"]=> string(10) "{author_1}" } ]



        // var_dump($clean_data);
        // die();

        // array(
        //     [],
        //     [],
        //     [],
        
        // )


        // $campaign_id = 33;

        // $data = array(
        //     'source_headers' => ['feature', 'company', 'price', 'firstname', 'company_two'],
        //     'rows' => [
                
        //         [ 'Fast accelration', "Tesla Motors", '500000k', 'John' , "Ford Motors"],   // represents each row
        //         [ 'Top speed', "Ferarri Motors", '500000k', 'Henry' , "Volswagon Motors"], 
        //         [ 'Ultimate Luxury', "Mercedes", '500000k', 'KElvin' , "Toyota Lexus"],  
        //         [ 'Relibable and Durable', "Honda Motors", '500000k', 'Jonathan' , "Toyota Moters"],
        //         [ 'Affordability', "Honda Motors", '500000k', 'Nicholas' , "Suzuki Motors corp"],   
        //     ]
        // );

        // CreateTemplateOnWebsite::dispatch($campaign_id, $data);
        

        


    }



}
