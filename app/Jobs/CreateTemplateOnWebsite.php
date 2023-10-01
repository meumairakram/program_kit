<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;



use App\Models\Campaign;
use App\Models\CampMap;
use App\Models\WebsitesInfo;
use App\Models\CampExecLog;

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

        foreach($campMapping as $index => $item) {

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
            'data_rows' => []
        );

        $loopIndex = 0;

        $campExecLogs = [];

        if(!array_key_exists('rows', $data)) {
            return;
        }

        
        foreach($data['rows'] as $datarow) {

            $variablesMap = [];
            $current_row = [];

            foreach($varIndexMap as $varIndex) {

                if($loopIndex == 0) {
                    $variablesMap[] = $varIndex['var'];
                }

                if(array_key_exists(intval($varIndex['header_index']), $datarow)) {
                    
                    $current_row[] = $datarow[intval($varIndex['header_index'])];
                
                } else {
                    $current_row[] = '';
                }

            }

            if($loopIndex == 0) {
                $requestdataPrep['variables'] = $variablesMap;
            }

            $requestdataPrep['dataset'][] = $current_row;


            $campExecLogs[] = CampExecLog::create(
                array(
                    'campaign_id' => $campaign_id,
                    'ds_type' => $source_type,
                    'data_address' => $loopIndex,
                    'exec_type' => $job_type,
                    'status' => 'inprogress'
                )
            );

            $loopIndex++;
        } 


        $requestdataPrep['template_id'] = $campaign->wp_template_id;

        $response = $websiteHelper->createTemplateFromValues($requestdataPrep);

        // foreach($campExecLogs as $execLog) {
        //     CampExecLog::create($execLog);
        // }

        
    }
}
