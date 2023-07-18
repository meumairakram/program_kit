<?php

namespace App\Http\Controllers;

use App\Models\WebsitesInfo;
use Illuminate\Http\Request;
use App\Models\Campaign ;
use App\Models\Datasources;
use Illuminate\Support\Facades\Auth;



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

        


        return redirect()->route('campaign-management')->with('success', 'Campaign created successfully!');
        // return view('dashboard-pages/campaign-management')->with('success', 'Campaign created successfully.');
        // store-campaign



    }


}
