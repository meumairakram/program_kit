<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


use App\Models\User;
use App\Models\CampaignExecStatus;
use App\Models\Datasources;
use App\HelperClasses\GoogleSheetHelpers;
use App\Models\Campaign;

use App\Jobs\CreateTemplateOnWebsite;

class StartCampaignSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $camp_id, $user;
    /**
     * Create a new job instance.
     */
    public function __construct($camp_id, $user)
    {
        //  

        $this->camp_id = $camp_id;
        $this->user = $user;

    }


    public function failed(\Exception $e) {

        CampaignExecStatus::setCampaignStatus($this->camp_id, 'failed');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        // $user = User::where(['id' => 2])->first();    // temp set user
        $user = $this->user;
        $campaign_id = $this->camp_id;

        $campaign = Campaign::where(['id' => $this->camp_id])->first();

        if(!$campaign) {

            throw new \Exception("Campaign with ID: $campaign_id Not found.");
            
        }

        $data_source = Datasources::where(['id' => $campaign->data_source_id])->first();


        if(!$data_source) {
            // 

            throw new \Exception("Datasource $campaign->data_source_id Not found.");
            
        }


        if($data_source->type !== 'google_sheet') {

            throw new \Exception("Only Gsheet data types are supported.");
            
        }

        $sheet_id = $data_source->sheet_id;

        
        // Set campaign status to syncing 
        CampaignExecStatus::setCampaignStatus($campaign_id, 'syncing');


        $sheetsHelper = new GoogleSheetHelpers($user);

        $data = $sheetsHelper->ReadAllDataFromSheet($sheet_id);


        if(!$data || !is_array($data) || count($data) < 1) {

            echo "Sheet is empty";
            throw new \Exception("Sheet is empty");
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


        CampaignExecStatus::where(['campaign_id' => $campaign_id])->update(['found_records' => count($clean_data)]);

        
        // var_dump($job_data);
        CreateTemplateOnWebsite::dispatch($campaign_id, $job_data);

        // Log::debug("Sent data" . json_encode($job_data));





    }
}
