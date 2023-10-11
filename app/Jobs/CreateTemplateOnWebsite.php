<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;

use App\Models\Campaign;
use App\Models\CampMap;
use App\Models\WebsitesInfo;
use App\Models\CampExecLog;
use App\Models\CampaignExecStatus;
   

use App\HelperClasses\WebsiteHelpers;




class CreateTemplateOnWebsite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $campaign_id, $data;  // add 10 rows at once for now.

    /**
     * Create a new job instance.
     */
    public function __construct($campaign_id, $data)
    {
        //
        $this->campaign_id = $campaign_id;
        $this->data = $data;


    }


    public function failed(\Exception $e) {

        CampaignExecStatus::setCampaignStatus($this->campaign_id, 'failed');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        $data = $this->data;
        $campaign_id = $this->campaign_id;

        $source_type = "gsheet";
        $job_type = "new";

        $campaign = Campaign::where(['id' => $campaign_id])->first();

        if(!$campaign) {
            // Error
        }

        $campMapping = CampMap::where(['campaign_id' => $campaign->id])->get();

        // $campMapping->count();
        // $campMapping->each(function($item) {

        // });

        $website = WebsitesInfo::where(['id' => $campaign->website_id])->first();

        $websiteHelper = new WebsiteHelpers($campaign->website_id);


        $varIndexMap = [];

        
        $sourceHeaders = $data['source_headers'];

        $existingExecInfo = CampExecLog::where(['campaign_id' => $campaign_id])->get();


        Log::debug("headers got" . serialize($sourceHeaders));

        
        // Its an additonal step for Google sheets which are created locally, as they are 1:1 mapped
        foreach($campMapping as $index => $item) {

            Log::debug("Searching for " . $item->field_header );
        
            $headerIndex = array_search($item->field_header, $sourceHeaders);

            if($headerIndex === false) {
            //    continue;
                $headerIndex = -1;
            }

            $varIndexMap[] = array(
                'var' => $item->template_variable,
                'header_index' => $headerIndex    
            );

        }   

        $requestdataPrep = array(
            'variables' => [],
            'dataset' => []
        );

        $loopIndex = 0;

        $campExecLogs = [];

        if(!array_key_exists('rows', $data)) {
            return;
        }

        
        foreach($data['rows'] as $row_info) {

            $variablesMap = [];
            $current_row = [];

            $datarow = $row_info['data'];
            $row_number = $row_info['row_number'];

            $hasValue = false;
            foreach($varIndexMap as $varIndex) {

                if($loopIndex == 0) {
                    $variablesMap[] = $varIndex['var'];
                }

                if(array_key_exists(intval($varIndex['header_index']), $datarow)) {
                    
                    if($datarow[intval($varIndex['header_index'])]) {
                        $hasValue = true;                    
                    }

                    $current_row[] = $datarow[intval($varIndex['header_index'])];

                } else {
                    $current_row[] = '';
                }

            }

            if($loopIndex == 0) {
                $requestdataPrep['variables'] = $variablesMap;
            }

            $alreadyExecuted = $existingExecInfo->filter(function($value, $key) use($row_number) {
                return $value->data_address == $row_number;
            })->count();

            if( $hasValue && $alreadyExecuted < 1) {

                $campExecLogs[] = array(
                    'campaign_id' => $campaign_id,                    
                    'ds_type' => $source_type,
                    'data_address' => $row_number,
                    'exec_type' => $job_type,
                    'status' => 'inprogress'
                );

                $requestdataPrep['dataset'][] = $current_row;

            }            

            $loopIndex++;
        } 


        $requestdataPrep['template_id'] = $campaign->wp_template_id;

        if(count($requestdataPrep['dataset']) > 0) {
            // Dont send call if there is no data set    
            $response = $websiteHelper->createTemplateFromValues($requestdataPrep);
        
        }

        CampExecLog::insert( $campExecLogs);

        CampaignExecStatus::setCampaignStatus($campaign_id, 'synced');
        
    }
}
