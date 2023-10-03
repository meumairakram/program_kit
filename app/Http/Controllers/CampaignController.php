<?php

namespace App\Http\Controllers;

use DB;
use App\Models\CampMap;
use App\Models\Template;
use App\Models\Campaign ;
use App\Models\Datasources;
use App\Models\AuthTokens;
use App\Models\WebsitesInfo;
use Illuminate\Http\Request;
use App\Models\DataSourceField;
use Illuminate\Support\Facades\Auth;


class CampaignController extends Controller {
    //

    // Auth::user()

    public function manage() {

        $current_user_id = Auth::user()->id;
    //    $campaigns = Campaign::where('owner_id', $current_user_id)->get();
        $campaigns = Campaign::join('user_websites', 'campaigns.website_id', '=', 'user_websites.id')
        ->join('user_datasources', 'campaigns.data_source_id', '=', 'user_datasources.id')
        ->where('campaigns.owner_id', $current_user_id)
        ->select(
            'user_datasources.type as data_source_type',
            'user_datasources.name as name',
            'user_websites.website_name as website_name',
            'user_websites.website_url as website_url',
            'campaigns.*'
        )
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
    //    return $request;
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

      

        $source_maps_fields = array();
        $data_maps = json_decode($request->input('data_maps_json'), true);

        foreach($data_maps as $map) {

            $variable_name = $map[0];
            $header_name = $map[1];

            $source_maps_fields[] = array(

                'campaign_id' => $campaign->id,
                'data_source' => $variable_name,
                'data_source_headers' => $header_name,  
            
            );
        }


        // Create data source fields mapping
        DataSourceField::insert($source_maps_fields);
        

        return redirect()->route('campaign-management')->with('message', 'Campaign created successfully!');
        // store-campaign
    }

    public function edit(Request $request, $id) 
    {
        $current_user_id = Auth::user()->id;
        //      $campaign = Campaign::where('id', $id)->first();
        $campaign = Campaign::join('user_websites', 'campaigns.website_id', '=', 'user_websites.id')
            ->join('user_datasources', 'campaigns.data_source_id', '=', 'user_datasources.id')
            ->where('campaigns.id', $id)
            ->select(
                'user_datasources.type as data_source_type',
                'user_datasources.id as data_source_id',
                'user_datasources.name as name',
                'user_websites.website_name as website_name',
                'user_websites.id as website_id',
                'user_websites.website_url as website_url',
                'campaigns.*'
            )
            ->first();

        $allDatasources = Datasources::where("owner_id", "=", $current_user_id)->get();
        $mapData = DataSourceField::where(['campaign_id' => $id])->select('id', 'campaign_id', 'data_source', 'data_source_headers')->get();

        $get_auth_token = AuthTokens::where('owner_id', '=', $current_user_id)->first();
        $google_acc_connected = $get_auth_token ? true : false;

        return view('dashboard-pages/edit-campaign',compact(["campaign", "allDatasources", "google_acc_connected", 'mapData']));
    }

    public function update(Request $request, Campaign $campaign) 
    {
        return $request;
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
        $campaignid = Campaign::where('id', $request->id)->first();

        $user = Auth::user();
        $campaign =  Campaign::find($request->id);
        $campaign->title = $attributes['title'];
        $campaign->description = $attributes['description'];
        $campaign->website_type = $attributes['website_id'];
        $campaign->website_id = $request->website_id;
        $campaign->post_type = $attributes['post_type'];
        $campaign->wp_template_id = $attributes['wp_template_id'];
        $campaign->data_source_id = $attributes['selected_datasource_id'];
        $campaign->status = 'ready';
        $campaign->owner_id = $user->id;
        $campaign->save();

        $source_maps_fields = array();
        $data_maps = json_decode($request->input('data_maps_json'), true);
        return $data_maps;
        foreach ($data_maps as $map) {
            $variable_name = $map[0];
            $header_name = $map[1];
        
            $source_maps_fields[] = array(
                'campaign_id' => $campaign->id,
                'data_source' => $variable_name,
                'data_source_headers' => $header_name,
            );
        }
        
        // Update existing records one by one using the array
        foreach ($source_maps_fields as $mapping) {
            return $mapping;
            DataSourceField::where('campaign_id', $campaign->id)
                ->update([
                    'data_source' => $mapping['data_source'],
                    'data_source_headers' => $mapping['data_source_headers'],
                ]);
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

}
