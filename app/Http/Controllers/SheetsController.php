<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\Google\Sheets\Facades\Google;
use Google\Client;
// use Sheets\Facades\Sheets;
use App\Models\AuthTokens;

class SheetsController extends Controller
{
    //  

    public function init(Request $request) {

        
        $redirect_uri = $request->redirect_url && $request->redirect_url != "" ? $request->redirect_url : env("APP_URL") . "sheets/init";
        $redirect_path = isset(parse_url($redirect_uri)['path']) ? parse_url($redirect_uri)['path'] : "";


        $getAccessTokens = $request->user()->google_access_token()->where(['auth_type' => 'google_oauth'])->get();

       
     
        $authCodeDetected = $this->detectAuthCodeAndSave($request);

        // var_dump($request->path() ); die();


        if($authCodeDetected) {

            if(parse_url($request->url())['path'] == $redirect_path) {
                echo "You google account is now connected.";
                die();
            } 

            var_dump("REdirecting to" . $redirect_uri); die();
            // return redirect($redirect_uri);
        //    return redirect($redirect_uri);
        
        }


        if(!count($getAccessTokens)) {
        
            $client = new Client(array(
                'application_name' => env("GOOGLE_APPLICATION_NAME"),
                "client_id" => env("GOOGLE_CLIENT_ID"),
                "client_secret" => env("GOOGLE_CLIENT_SECRET"),
                // "redirect_uri" => $redirect_uri,
                "redirect_uri" => 'https://pkit.codeivo.com/sheets/init',
                "scopes" => [\Google\Service\Sheets::DRIVE, \Google\Service\Sheets::SPREADSHEETS],
                "access_type" => "online",
                "approval_prompt" => "auto"

            ));

            return redirect($client->createAuthUrl());
        
        
        }

        if(parse_url($request->url())['path'] == $redirect_path) {
            echo "You google account is already connected.";
            die();
        }    

        return redirect($redirect_uri);


    }



    public function detectAuthCodeAndSave($request) {

        $accessCode = $request->code;
        $allowedScopes = $request->scope;
        $getAccessTokens = $request->user()->google_access_token()->where(['auth_type' => 'google_oauth'])->get();


        if($accessCode) {

            if(count($getAccessTokens) < 1) {

                

                $allowedScopesArr = $allowedScopes != "" && $allowedScopes ? explode(" ", $allowedScopes) : [];
                //var_dump(explode(" ",$allowedScopes)); die();

                $requiredScopesGranted = array_intersect([
                    'https://www.googleapis.com/auth/drive',
                    'https://www.googleapis.com/auth/spreadsheets'
                ], $allowedScopesArr);

                if(count($requiredScopesGranted) < 2) {

                    var_dump("Authentication Failed! You need to provide both Google Sheets and Google Drives Access scopes in order for the app to work. Please click below and reauthenticate.");
                    die();
                }

                // match recieved scopes 

                // save code recieved
                $saveAuthToken = AuthTokens::create(array(
                    'owner_id' => $request->user()->id,
                    'auth_type' => 'google_oauth',
                    'key_type' => 'access_token',
                    'key_value' => $accessCode
                ));

                $saveAuthToken->save();

            } 
            // Save access token to this user here.
            var_dump("You are now connected");
            return true;

        }

        return false;
    
    }

}
