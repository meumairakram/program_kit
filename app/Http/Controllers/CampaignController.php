<?php

namespace App\Http\Controllers;

use App\Models\WebsitesInfo;
use Illuminate\Http\Request;
use App\Models\Campaign ;
use App\Models\Datasources;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleSheetController;


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
            'type' => ['required'],
            'website' => ['required'],
            'wp_template_id' => ['required'],
            'data_source' => ['required'],
            'description' => []
        ]);


        if(!Auth::check()) {

            // User not logged in

        }


        $user = Auth::user();


        $campaign = new Campaign();
        $campaign->title = $attributes['title'];
        $campaign->description = $attributes['description'];
        $campaign->wp_template_id = $attributes['wp_template_id'];
        $campaign->type = $attributes['type'];
        $campaign->status = 'ready';
        $campaign->owner_id = $user->id;
        

        $campaign->save();
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
