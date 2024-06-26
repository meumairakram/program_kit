<?php

namespace App\Http\Controllers;

use App\Models\Datasources;
use Illuminate\Http\Request;
use App\Models\Campaign ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;



class DatasourcesController extends Controller {
    //

    // Auth::user()

    public function manage() {

        $current_user_id = Auth::user()->id;
        $datasources = Datasources::where('owner_id', $current_user_id)->get();       

        return view('dashboard-pages/datasources/manage',array(
            'datasources' => $datasources
        ));
    }


    public function add_new() {

        return view('dashboard-pages/datasources/add-datasource',array(
            
        ));

    }

    public function store(Request $request) {

        $attributes  = $request->validate([
            'name' => ['required'],
            'type' => ['required'],
            // 'website_url' => ['required'],
            // 'ajax_url' => ['required'],
            // 'authentication_key' => ['required'],


        ]);


        $customErrors = new MessageBag();

        if(!Auth::check()) {

            // User not logged in
            $customErrors->add("some_error", "You are not authroized");

        }




        $csv_data = $request->file('csv_file');

        if(!$csv_data) {

          $customErrors->add("some_error", "CSV is Invalid.");
        
        }

        $errors = $request->session()->get('errors', new MessageBag())->merge($customErrors);

        if($errors->any()) {


            return redirect()->back()->withErrors($errors);
        }

        $csvData = array_map("str_getcsv", file($csv_data));

        $recordCount = count($csvData) - 1;

        $user = Auth::user();



        $randomKey = uniqid();

        // Get the user's ID to create a user-specific directory
        $userId = $user->id;

        // Create the directory if it doesn't exist
        $directory = "storage/user_datasources/{$userId}";
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $originalFileName = $csv_data->getClientOriginalName();
        $cleanedFileName = Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME));
        $fileExtension = $csv_data->getClientOriginalExtension();
        
        $finalFileName = "{$cleanedFileName}_{$randomKey}.{$fileExtension}";


        $filePath = $csv_data->storeAs($directory, $finalFileName);


        $datasource = new Datasources();
        $datasource->name = $request->input('name');
        $datasource->type = $request->input('type');
        $datasource->owner_id = $user->id;
        $datasource->requires_mapping = false;
        $datasource->records_count = $recordCount;
        $datasource->file_path = $filePath; // Save the file path in the database
        $datasource->last_synced = now();


        $datasource->save();


        // title

        


        return redirect()->route('manage-datasources')->with('success', 'Data source added successfully!');
        // return view('dashboard-pages/campaign-management')->with('success', 'Campaign created successfully.');
        // store-campaign



    }


}
