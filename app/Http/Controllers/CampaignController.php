<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Support\Facades\Http;

use App\Models\CampMap;
use App\Models\Template;
use App\Models\Campaign ;
use App\Models\Datasources;
use App\Models\AuthTokens;
use App\Models\WebsitesInfo;
use Illuminate\Http\Request;
use App\Models\DataSourceField;
use Illuminate\Support\Facades\Auth;
use App\Models\CampaignExecStatus;
use App\Models\CampExecLog;

use App\Jobs\StartCampaignSync;

class CampaignController extends Controller {
    //

    // Auth::user()

    public function manage() {

        $current_user_id = Auth::user()->id;
    //    $campaigns = Campaign::where('owner_id', $current_user_id)->get();
        $campaigns = DB::table('campaigns')
            ->leftjoin('user_datasources', 'campaigns.data_source_id', 'user_datasources.id')
            ->leftjoin('templates', 'campaigns.wp_template_id','templates.template_id')
            ->leftjoin('user_websites', 'campaigns.website_id', 'user_websites.id')
            ->leftjoin('campaign_exec_status', 'campaigns.id', 'campaign_exec_status.campaign_id')
            // ->where("user_websites.is_authenticated", "Verified")
            ->where('campaigns.owner_id', $current_user_id)
            ->select(
                'campaigns.*',
                'user_datasources.name as dataSourceName',
                'user_datasources.type as dataSourceType',
                'templates.template as templateName',
                'templates.template_variables as variables',
                'user_websites.website_name as website_name',
                'user_websites.website_url as website_url',
                'campaign_exec_status.status as exec_status'
            )
            ->orderBy('campaigns.id', 'ASC')
            //->distinct()
            ->get();

        
     
        return view('dashboard-pages/campaign-management',array(
            'campaigns' => $campaigns
        ));
    }


    public function create() {


        $current_user_id = Auth::user()->id;


        $allWebsites = WebsitesInfo::where("owner_id", "=", $current_user_id)->get();

        $allDatasources = Datasources::where("owner_id", "=", $current_user_id)->get();

        $get_auth_token = AuthTokens::where('owner_id', '=', $current_user_id)->first();
        $google_acc_connected = $get_auth_token ? true : false;


        return view('dashboard-pages/create-campaign',compact(["allWebsites", "allDatasources", "google_acc_connected"]));

    }

    public function store(Request $request) 
    {
       
        $attributes  = $request->validate([
            'title' => [''],
            'description' => [],
            'website_type' => [''],
            'website_id' => [''],
            'post_type' => [''],
            'wp_template_id' => [''],
            'selected_datasource_id' => [''],
            'data_maps_json' => ['']
        ]);

        if(!Auth::check()) {
            // User not logged in
        }

        $user = Auth::user();
        $campaign = new Campaign();
        $campaign->title = $attributes['title'];
        $campaign->description = $attributes['description'];
        $campaign->type = $attributes['website_type'];
        $campaign->website_id = $attributes['website_id'];
        $campaign->post_type = $attributes['post_type'];
        $campaign->wp_template_id = $attributes['wp_template_id'];
        $campaign->data_source_id = $attributes['selected_datasource_id'];
        $campaign->status = 'ready';
        $campaign->owner_id = $user->id;
        $campaign->save();


        // create campaign status record
        CampaignExecStatus::create([
            'campaign_id' => $campaign->id,
            'status' => 'idle'
        ]);


      

        $source_maps_fields = array();
        $data_maps = json_decode($request->input('data_maps_json'), true);

        foreach($data_maps as $map) {

            $variable_name = $map[0];
            $header_name = $map[1];

            $source_maps_fields[] = array(

                'campaign_id' => $campaign->id,
                'template_variable' => $variable_name,
                'field_header' => $header_name,  
                'val_type' => 'string'
            );
        }


        // Create data source fields mapping
        CampMap::insert($source_maps_fields);
        

        return redirect()->route('campaign-management')->with('message', 'Campaign created successfully!');
        // store-campaign
    }

    public function edit(Request $request, $id) {

        $current_user_id = Auth::user()->id;
//      $campaign = Campaign::where('id', $id)->first();
        $campaign = DB::table('campaigns')
            ->leftjoin('data_source_fields', 'campaigns.data_source_id', '=', 'data_source_fields.data_source_id')
            ->leftjoin('user_datasources', 'campaigns.data_source_id', 'user_datasources.id')
            ->leftjoin('templates', 'campaigns.wp_template_id', '=', 'templates.template_id')
            ->leftjoin('user_websites', 'campaigns.website_id', '=', 'user_websites.id')
            ->where('campaigns.id', $id)
            ->select(
                'data_source_fields.data_source as dataSourceName',
                'data_source_fields.data_source_headers as data_source_headers',
                'user_datasources.type as type',
                'user_datasources.id as id',
                'templates.template as templateName',
                'templates.template_variables as variables',
                'user_websites.website_name as website_name',
                'user_websites.website_url as website_url',
                'campaigns.*'
            )
            ->first();

        $allWebsites = WebsitesInfo::where("owner_id", "=", $current_user_id)->get();
        $allDatasources = Datasources::where("owner_id", "=", $current_user_id)->get();

        $datasources = DB::table('user_datasources')->where('id', $campaign->data_source_id)->first();
        $file_path = $datasources->file_path; 
        $absolute_file_path = storage_path('app/'.$file_path);

        $csvData = array_map("str_getcsv", file($absolute_file_path));
        $csvHeaders = $csvData[0];
        $first_10_records = array_slice($csvData, 1, 10);

        return view('dashboard-pages/edit-campaign',compact(["campaign", "allWebsites", "allDatasources", "absolute_file_path", "csvHeaders", "first_10_records"]));
    }

    public function update(Request $request, Campaign $campaign) 
    {
        // return $request;
        if(!Auth::check()) {
           // User not logged in
        }
        $campaignid = Campaign::where('id', $request->id)->first();

        $user = Auth::user();
        $campaign =  Campaign::find($request->id);
        $campaign->title = $request->title;
        $campaign->description = $request->description;
        $campaign->website_type = $request->website_type;
        $campaign->website_id = $request->website_id;
        $campaign->post_type = $request->post_type;
        $campaign->wp_template_id = $request->wp_template_id;
        $campaign->data_source_id = $request->data_source_id;
        $campaign->status = 'ready';
        $campaign->owner_id = $user->id;
        $campaign->save();

        $templateid= Template::where('template_id', $campaignid->wp_template_id)->where('owner_id', $user->id)->first();

        $template = Template::find($templateid->id);
        $template->template_id = $request->wp_template_id;;
        $template->template = $request->template_name;
        $template->template_variables = $request->variables;
        $template->owner_id = $user->id;
        $template->save();

        $datasourceid = DataSourceField::where('data_source_id', $campaignid->data_source_id)->where('owner_id', $user->id)->first();

        $dataSource = DataSourceField::find($datasourceid->id);
        $dataSource->data_source_id = $request->data_source_id;
        $dataSource->data_source = $request->data_source_name;
        $dataSource->data_source_headers = $request->data_source_headers;
        $dataSource->owner_id = $user->id;
        $dataSource->save();

        $source_maps_fields = array();
        $data_maps = json_decode($request->input('data_maps_json', true));

        foreach($data_maps as $map) {

            $variable_name = $map[0];
            $header_name = $map[1];

            $source_maps_fields[] = array(

                'campaign_id' => $campaign->id,
                'data_source' => $variable_name,
                'data_source_headers' => $header_name,  
            
            );
        }

        return redirect()->route('campaign-management')->with('message', 'Campaign Updated successfully!');
    }

    public function delete(Request $request, $id)
    {
        Campaign::destroy(array('id',$id));
        $current_user_id = Auth::user()->id;
        $campaigns = Campaign::where('owner_id', $current_user_id)->get();
        return redirect()->route('campaign-management',array(
            'campaigns' => $campaigns
        ))->with('message', 'Campaign Deleted successfully!');

    }

    public function selectWebSite(Request $request)
    {
        $type = $request->input('type');

        if(!$type) {
            return response()->json([
                "success" => false,
                "data" => []
            ]);
        }

        $current_user_id = Auth::user()->id;
        $websites = WebsitesInfo::where("owner_id", "=", $current_user_id)->where("type", $type)->get(); //->where("is_authenticated", "Verified")
        return response()->json([
            "success" => true,
            "websites" => $websites
        ]);
    }





    public function campaignStatusPage(Request $request, $camp_id) {

   

        $campaign_data = Campaign::where(['campaigns.id' => $camp_id])
            ->leftjoin('user_datasources', 'campaigns.data_source_id', 'user_datasources.id')
            ->leftjoin('user_websites', 'campaigns.website_id', 'user_websites.id')
            ->leftjoin('campaign_exec_status', 'campaigns.id', 'campaign_exec_status.campaign_id')
            ->get([
                'campaigns.*',
                'user_websites.*',
                'campaign_exec_status.status as exec_status'
            ]);


        // var_dump($campaign_data); die();
            

        return view('dashboard-pages/campaign-status', compact('campaign_data'));
            
            // ->leftjoin('templates', 'campaigns.wp_template_id','templates.template_id')


    
    }




        
    public function data_api_campaign_info(Request $request, $camp_id) {

        //   title:"",
        //     status: "",
        //     website_pinged: false,
        //     pages_published: 0,

        $campaign = Campaign::leftjoin('campaign_exec_status', 'campaigns.id', 'campaign_exec_status.campaign_id')
        ->where(['campaigns.id' => $camp_id])
        ->get([
            'campaigns.*',
            'campaign_exec_status.status as exec_status'
        ])->first();


        if(!$campaign) {
            response()->json(array(
                'success' => false,
                'error' => "Invalid campaign",
                'data' => []
            
            ));
        }    

        $pages_count = CampExecLog::where(['campaign_id' => $camp_id])->get()->count();


        return response()->json(array(
            'success' => true,
            'data' => [
                'title' => $campaign->title,
                'status' => $campaign->exec_status ? $campaign->exec_status : "N/A",
                'pages_published' =>  $pages_count 
            ],
            'error' => null
        
        ));

        


    }
    


    
        
    public function data_api_campaign_ping(Request $request, $camp_id) {

        //   title:"",
        //     status: "",
        //     website_pinged: false,
        //     pages_published: 0,

        $campaign = Campaign::where(['id' => $camp_id])->first();


        if(!$campaign) {
            response()->json(array(
                'success' => false,
                'error' => "Invalid campaign",
                'data' => []
            
            ));
        }   

        $website =  WebsitesInfo::where(['id' => $campaign->website_id])->first();

        if(!$website) {
            response()->json(array(
                'success' => false,
                'error' => "Invalid website",
                'data' => []
            
            ));
        }   

        
        $website_url = $website->request_url;
        $call_success = false;

        $before_time = time();
        try {
            $http_call = Http::get($website_url);
            $call_success = true;

        } catch(\Exception $e) {
            return response()->json(array(
                'success' => false,
                'data' => [
                   
                ],
                'error' => $e->getMessage()
            
            ));
        }
        $after_time = time();
        


        return response()->json(array(
            'success' => $call_success,
            'data' => [
                'pinged_at' => $website_url,
                'query_time' => $after_time - $before_time
            ],
            'error' => null
        
        ));

        


    }


    // public function start camp sync
    public function data_api_campaign_start(Request $request, int $camp_id): \Illuminate\Http\JsonResponse {

        $campaign_status = CampaignExecStatus::where(['campaign_id' => $camp_id])->first();
        $started = false;

        if($campaign_status->status == 'idle') {
            
            StartCampaignSync::dispatch($camp_id, Auth::user());
            $started = true;
        }



        return response()->json([
            'success' => $started,
            'data' => [],
            'error' => $started ? null : "Campaign sync is already in progress"

        ]);        

    }






}
