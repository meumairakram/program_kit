<?php

namespace App\Http\Controllers;

use App\Models\Datasources;
use Illuminate\Http\Request;
use App\Models\Campaign ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $accessTokens = auth()->user()->google_access_token()->where(['auth_type' => 'google_oauth']);

        $google_account_connected = $accessTokens->count() > 0;

        // var_dump($google_account_connected); die();


        return view('dashboard-pages/datasources/add-datasource',array(
            'google_account' => $google_account_connected
            // 'google_account' => false
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

        return redirect()->route('manage-datasources')->with('message', 'Data source added successfully!');
        // return view('dashboard-pages/campaign-management')->with('success', 'Campaign created successfully.');
        // store-campaign
    }

    public function edit(Request $request, $id) {

        $datasources = DB::table('user_datasources')->where('id', $id)->first();
        $file_path = $datasources->file_path; 
        $absolute_file_path = storage_path('app/'.$file_path);

        $csvData = array_map("str_getcsv", file($absolute_file_path));
        $csvHeaders = $csvData[0];
        $first_10_records = array_slice($csvData, 1, 10);

        return view('dashboard-pages/datasources/edit-datasource',[
            'datasource' => $datasources,
            'absolute_file_path' => $absolute_file_path,
            'csvHeaders' => $csvHeaders,
            'first_10_records' => $first_10_records
        ]);

    }

    public function update(Request $request, Datasources $datasources) {

        $customErrors = new MessageBag();
        if(!Auth::check()) {
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
        $userId = $user->id;

        $directory = "storage/user_datasources/{$userId}";
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $originalFileName = $csv_data->getClientOriginalName();
        $cleanedFileName = Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME));
        $fileExtension = $csv_data->getClientOriginalExtension();
        $finalFileName = "{$cleanedFileName}_{$randomKey}.{$fileExtension}";
        $filePath = $csv_data->storeAs($directory, $finalFileName);

        $datasource =  Datasources::find($request->id);
        $datasource->name = $request->input('name');
        $datasource->type = $request->input('type');
        $datasource->requires_mapping = false;
        $datasource->records_count = $recordCount;
        $datasource->file_path = $filePath; 
        $datasource->last_synced = now();
        $datasource->save();

        return redirect()->route('manage-datasources')->with('message', 'Data source Updated successfully!');

    }

    public function delete(Request $request, $id) 
    {
        // $datasource =  Datasources::find($id);
        Datasources::destroy(array('id',$id));
        $current_user_id = Auth::user()->id;
        $datasources = Datasources::where('owner_id', $current_user_id)->get();  

        return redirect()->route('manage-datasources',array(
            'datasources' => $datasources
        ))->with('message', 'Data source Deleted successfully!');
    }

}
