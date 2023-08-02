<?php

namespace App\Http\Controllers;

use App\Models\Campaign ;
use App\Models\WebsitesInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
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
            'verified' => ["required"]
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
        $website->is_authenticated = $attributes['verified'] == "1" ? "yes" : "no";
        $website->owner_id = $user->id;
        $website->save();

        return redirect()->route('website-management')->with('message', 'Website added successfully!');
        // return view('dashboard-pages/campaign-management')->with('success', 'Campaign created successfully.');
        // store-campaign

    }

    public function edit(Request $request, $id) {

        $websites = DB::table('user_websites')->where('id', $id)->first();

        return view('dashboard-pages/websites/edit-websites',[
            'websites' => $websites
        ]);

    }

    public function update(Request $request, WebsitesInfo $websitesInfo) {

        // return $request;
        
        $customErrors = new MessageBag();
        if(!Auth::check()) {
            $customErrors->add("some_error", "You are not authroized");
        }
        $user = Auth::user();

        $website =  WebsitesInfo::find($request->id);
        $website->website_name = $request->title;
        $website->type = $request->type;
        $website->website_url = $request->website_url;
        $website->request_url = $request->ajax_url;
        $website->authentication_key = $request->authentication_key;
        $website->is_authenticated = $request->verified == "1" ? "yes" : "no";
        $website->owner_id = $user->id;
        $website->save();

        return redirect()->route('website-management')->with('message', 'Website Updated successfully!');
    }

    public function delete(Request $request, $id) 
    {
        WebsitesInfo::destroy(array('id',$id));
        $current_user_id = Auth::user()->id;
        $websites = WebsitesInfo::where('owner_id', $current_user_id)->get();  
        return redirect()->route('website-management',array(
            'websites' => $websites
        ))->with('message', 'Website Deleted successfully!');

    }

}
