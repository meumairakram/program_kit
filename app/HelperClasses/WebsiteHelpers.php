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

        if($website_info->is_authenticated !== 'Verified') {
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


    public function get_post_types() {

        $postUrl = $this->websiteInfo->request_url ;

        $response = Http::withQueryParameters(['action', 'pseo_get_all_post_types'])->get($postUrl );

        return $response;

    
    }   


    public function get_templates_for_post_type($post_type) {

       return $this->doPost('', ['post_type' => $post_type], 'pseo_get_posts_by_type');

    }    


    public function get_template_variables_array($template_id) {

        $action = 'pseo_get_post_variables';

        $ajax_url = sprintf('%s?action=%s',$this->websiteInfo->request_url, $action);

        $response = Http::asForm()
        ->post($ajax_url, ['post_id' => $template_id]);

         if ($response->successful()) {
            $responseData = $response->json();


            // Check if success is true in the JSON response
            if ($responseData['success']) {
                // Extract variables from the data array
                $variables = $responseData['data']['variables'];

                // You now have the variables in a PHP array
                return $variables;
            } else {
                // Handle the case where success is not true
                return [];
            }

        } else {
            // Handle the case where the request was not successful
            return [];
        }

// http://wpsandbox.local//wp-admin/admin-ajax.php?action=pseo_get_post_variables

    }

    




}