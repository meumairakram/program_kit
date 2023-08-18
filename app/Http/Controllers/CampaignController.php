<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Campaign ;
use App\Models\Datasources;
use App\Models\WebsitesInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSourceField;


class CampaignController extends Controller {
    //

    // Auth::user()

    public function manage() {

        $current_user_id = Auth::user()->id;
        $campaigns = Campaign::where('owner_id', $current_user_id)->get();       

        return view('dashboard-pages/campaign-management',array(
            'campaigns' => $campaigns
        ));
    }


    public function create() {


        $current_user_id = Auth::user()->id;


        $allWebsites = WebsitesInfo::where("owner_id", "=", $current_user_id)->get();

        $allDatasources = Datasources::where("owner_id", "=", $current_user_id)->get();


        return view('dashboard-pages/create-campaign',compact(["allWebsites", "allDatasources"]));

    }

    public function store(Request $request) {

        $attributes  = $request->validate([
            'title' => ['required'],
            'description' => [],
            'website_type' => [''],
            'website_id' => [''],
            'post_type' => [''],
            'wp_template_id' => [''],
            'data_source_id' => [''],
        ]);

        if(!Auth::check()) {
            // User not logged in
        }

        $user = Auth::user();
        $campaign = new Campaign();
        $campaign->title = $attributes['title'];
        $campaign->description = $attributes['description'];
        $campaign->website_type = $attributes['website_type'];
        $campaign->website_id = $attributes['website_id'];
        $campaign->post_type = $attributes['post_type'];
        $campaign->wp_template_id = $attributes['wp_template_id'];
        $campaign->data_source_id = $attributes['data_source_id'];
        $campaign->status = 'ready';
        $campaign->owner_id = $user->id;
        $campaign->save();

        $variables = '';
        if ($request->has('template_variables') && is_array($request->input('template_variables'))) {
            foreach ($request->input('template_variables') as $templateVariable) {
                $variables = $templateVariable;
            }
        }

        $template = new Template();
        $template->template_id = $attributes['wp_template_id'];
        $template->template = $request->template_name;
        $template->template_variables = $request->input('template_variables');
        $template->save();

        $template = new DataSourceField();
        $template->data_source_id = $attributes['data_source_id'];
        $template->data_source = $request->data_source_name;
        $template->data_source_headers = $request->input('data_source_headers');
        $template->save();
        
        // title
        return redirect()->route('campaign-management')->with('message', 'Campaign created successfully!');
        // return view('dashboard-pages/campaign-management')->with('success', 'Campaign created successfully.');
        // store-campaign
    }

    public function edit(Request $request, $id) {

        $current_user_id = Auth::user()->id;
        $campaign = Campaign::where('id', $id)->first();       
        $allWebsites = WebsitesInfo::where("owner_id", "=", $current_user_id)->get();
        $allDatasources = Datasources::where("owner_id", "=", $current_user_id)->get();
        return view('dashboard-pages/edit-campaign',compact(["campaign", "allWebsites", "allDatasources"]));
    }

    public function update(Request $request, Campaign $campaign) {

        if(!Auth::check()) {
           // User not logged in
        }

        $user = Auth::user();
        $campaign =  Campaign::find($request->id);
        $campaign->title = $request->title;
        $campaign->description = $request->description;
        $campaign->wp_template_id = $request->wp_template_id;
        $campaign->type = $request->type;
        $campaign->status = 'ready';
        $campaign->owner_id = $user->id;
        $campaign->save();

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
        $websites = WebsitesInfo::where("owner_id", "=", $current_user_id)->where("type", $type)->get();
        return response()->json([
            "success" => true,
            "websites" => $websites
        ]);
    }

}
