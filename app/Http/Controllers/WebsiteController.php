<?php

namespace App\Http\Controllers;

use App\Models\WebsitesInfo;
use Illuminate\Http\Request;
use App\Models\Campaign ;
use Illuminate\Support\Facades\Auth;


class WebsiteController extends Controller {
    //

    // Auth::user()

    public function manage() {

        $current_user_id = Auth::user()->id;
        $websites = WebsitesInfo::where('owner_id', $current_user_id)->get();       

        return view('dashboard-pages/websites/manage',array(
            'websites' => $websites
        ));
    }


    public function add_new() {

        return view('dashboard-pages/websites/add-website',array(
            
        ));

    }

    public function store(Request $request) {

        $attributes  = $request->validate([
            'title' => ['required'],
            'type' => ['required'],
            'website_url' => ['required'],
            'ajax_url' => ['required'],
            'authentication_key' => ['required'],
        ]);


        if(!Auth::check()) {

            // User not logged in

        }


        $user = Auth::user();


        $website = new WebsitesInfo();
        $website->website_name = $attributes['title'];
        $website->type = $attributes['type'];
        $website->website_url = $attributes['website_url'];
        $website->request_url = $attributes['ajax_url'];
        $website->authentication_key = $attributes['authentication_key'];
        $website->owner_id = $user->id;
        

        $website->save();


        // title

        


        return redirect()->route('website-management')->with('success', 'Website added successfully!');
        // return view('dashboard-pages/campaign-management')->with('success', 'Campaign created successfully.');
        // store-campaign



    }


}
