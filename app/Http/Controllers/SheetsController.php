<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\Google\Sheets\Facades\Google;
use Google\Client;
// use Sheets\Facades\Sheets;
use App\Models\AuthTokens;
use Google\Service\Sheets\Spreadsheet;
use Google\Service\Sheets\SpreadsheetProperties;
use Google\Service\Sheets;


class SheetsController extends Controller
{
    //  

    public function init(Request $request) {

        
        $redirect_uri = $request->redirect_url && $request->redirect_url != "" ? $request->redirect_url : env("APP_URL") . "sheets/init";
        $redirect_path = isset(parse_url($redirect_uri)['path']) ? parse_url($redirect_uri)['path'] : "";


        $getAccessTokens = $request->user()->google_access_token()->where(['auth_type' => 'google_oauth'])->get();

       
     
        $authCodeDetected = $this->detectAuthCodeAndSave($request);


        if($authCodeDetected) {

            if(parse_url($request->url())['path'] == $redirect_path) {
                echo "You google account is now connected.";
                die();
            } 

            if(session()->has('post_auth_redirect')) {
                return redirect(session()->get('post_auth_redirect') . '?google_auth=success');
            }
        
        }


        if(!count($getAccessTokens)) {

            $client = $this->getClient();

            session(['post_auth_redirect' => $redirect_uri]);

            return redirect($client->createAuthUrl());

        }



         if(parse_url($request->url())['path'] == $redirect_path) {
                
            echo "You google account is already connected.";    
            die();
        } 

        
        return redirect($redirect_uri . '?google_auth=success');


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


                $client = $this->getClient();

                $accessToken = $client->fetchAccessTokenWithAuthCode($accessCode);
                $refreshToken = "Not available";

                if(isset($accessToken['refresh_token'])) {
                    $refreshToken = $accessToken['refresh_token'];
                }

                if(!isset($accessToken['access_token']) && isset($accessToken['error'])) {
                
                        var_dump($accessToken['error']); die();
                }

                // save code recieved
                $saveAuthToken = AuthTokens::create(array(
                    'owner_id' => $request->user()->id,
                    'auth_type' => 'google_oauth',
                    'key_type' => 'access_token',
                    'key_value' => $accessToken['access_token'],
                    'refresh_token' => $refreshToken
                ));

                $saveAuthToken->save();

            } 

            // Save access token to this user here.
            return true;

        }

        return false;
    
    }




    public function getUserAccessToken() {

        $user = auth()->user();

        if(!$user) {

            die("Unauthorized");
        }

        $accessTokens = $user->google_access_token()->where(['auth_type' => 'google_oauth']);

        if($accessTokens->count() < 1) {

            return false;
        }

        return $accessTokens->first();
    
    }



    public function getClient() {

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

        return $client;
    
    }




    public function createNewGoogleSheet($title) {

        $accessToken = $this->getUserAccessToken();

        if(!$accessToken) {
            
            redirect('/sheets/init');

        }

        $client = $this->getClient();

        $client->setAccessToken($accessToken->key_value);

        $service = new Sheets($client);
     
        $spreadSheet = new Spreadsheet([
            'properties' => [
                'title' => $title
            ]
        ]);


        $created_sheet = $service->spreadsheets->create($spreadSheet, ['fields' => 'spreadsheetId']);

        if($created_sheet) {

            return $created_sheet->spreadsheetId;
        } 

        return false;
    
    }



    public function testRoute(Request $request) {
     
        var_dump($this->createNewGoogleSheet("Testing new sheet 2230")); die();
    
    }

}
