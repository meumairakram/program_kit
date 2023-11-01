<?php 

namespace App\HelperClasses;

use App\Models\WebsitesInfo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebsiteHelpers {

    public $websiteInfo;
    public function __construct(int $website_id) {

        $website_info = WebsitesInfo::where(['id' => $website_id])->first();

        if(!$website_info) {
            throw new \Exception("No website found with such ID");
            return;
        }

        if($website_info->is_authenticated !== 'yes') {
            throw new \Exception("Website is not authenticated");

            return;
        }


        $this->websiteInfo = $website_info;

    }


    public function createTemplateFromValues($params) {

        // Log::debug("Sending generatecontent call" . json_encode($params));
        $action_type = 'pseo_generate_content';        
          
        $response = $this->doPost('', $params, $action_type);

        return $response;        

    }


    public function doPost($path, $params, $action_type) {

        $postUrl = $this->websiteInfo->request_url . $path;

        $response = Http::withQueryParameters(['action' => $action_type])->post(trim($postUrl), $params);

        return $response;

    }







}