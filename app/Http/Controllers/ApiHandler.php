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
use App\Models\UserWebsiteKey;
use App\Models\CampaignExecSatus;


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

        if($datasource->type == "csv") {

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

        if($datasource->type == "google_sheet") {

            return response()->json([
                "success" => true,
                "data" => array(
                    "headers" => [],
                    "preview_rows" => []
                )
            ]);
        
        }


        

    
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

        $campaign_id = 35;

        $sheet_id = "1l_hs1QcqCvNnQUBs72ik3kFIKJEey7gkKp-M-EaDcrI";

        
        // Set campaign status to syncing 
        CampaignExecSatus::setCampaignStatus($campaign_id, 'syncing');


        $sheetsHelper = new GoogleSheetHelpers($user);

        $data = $sheetsHelper->ReadAllDataFromSheet($sheet_id);


        if(!$data || !is_array($data) || count($data) < 1) {

            echo "Sheet is empty";
            die();
        }

        $headers = $data[0];   // consider first row as header

        $content_data = array_slice($data, 1, count($data));


        $clean_data = [];
        $clean_headers = [];
        $plain_headers = [];
        
        foreach($headers as $index => $header_name) {
            if($header_name !== "") {
                $clean_headers[] = ['index' => $index, "header" => $header_name];                   
                $plain_headers[] = $header_name;
            }
        }   

        $row_number = 2; // starting from 2 as item 1 is headers
        foreach($content_data as $content_row) {

            $current_row = [];

            foreach($clean_headers as $header_data) {
                
                $target_index = $header_data['index'];
                $header_name = $header_data['header'];

                $current_row[] = array_key_exists($target_index, $content_row) ? $content_row[$target_index] : "";
            
            }

            $clean_data[] = array('data' => $current_row, 'row_number' => $row_number);
            
            $row_number++;
        
        }




        // need to split job data in sets of 20 for each job dispatch



        $job_data = array(
            'source_headers' => $plain_headers,
            'rows' => $clean_data
        );
        // var_dump($job_data);
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

    public function generateKey(Request $request)
    {
        // Generate a unique verification key
        $key = bin2hex(random_bytes(16));

        $user = Auth::user(); 
        $websiteUrl = $request->input('website_url');
        UserWebsiteKey::create([
            'owner_id' => $user->id,
            'website_url' => $websiteUrl,
            'verification_key' => $key,
        ]);

        return response()->json(['key' => $key]);
    }
    
    public function verifyWebsite(Request $request)
    {
        // Retrieve the user's code and website URL from the request
        $verificationKey = $request->input('verification_key');
        $websiteUrl = $request->input('website_url');

        $user = Auth::user(); 
        $verification = UserWebsiteKey::where('user_id', $user->id)
            ->where('website_url', $websiteUrl)
            ->where('verification_key', $verificationKey)
            ->first();

        if ($verification) {

            return response()->json(['message' => 'Website verified successfully']);
        } else {
            return response()->json(['message' => 'Verification failed'], 401);
        }
    }




}
