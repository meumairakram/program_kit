<?php

namespace App\HelperClasses;

use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\Log;



class GoogleSheetHelpers {

	public $user;

	public function __construct( $user ) {

		$this->user = $user;


	}


	public function getUserAccessToken() {

		$user = $this->user;
		

		if ( ! $user ) {
			throw new \Exception("No user found");
		}

		$accessTokens = $user->google_access_token()->where( [ 'auth_type' => 'google_oauth' ] );

		if ( $accessTokens->count() < 1 ) {

			throw new \Exception("No Access token found in database.");
		}

		
        
		return $accessTokens->first();

	}


	private function getClient() {

		$redirect_url = env( 'APP_ENV' ) == "local" ? 'http://localhost/pkit' : 'https://pkit.codeivo.com/sheets/init';


		$client = new Client( array(
			'application_name' => env( "GOOGLE_APPLICATION_NAME" ),
			"client_id" => env( "GOOGLE_CLIENT_ID" ),
			"client_secret" => env( "GOOGLE_CLIENT_SECRET" ),
			"redirect_uri" => $redirect_url,
			// "redirect_uri" => 'https://pkit.codeivo.com/sheets/init',
			"scopes" => [ \Google\Service\Sheets::DRIVE, \Google\Service\Sheets::SPREADSHEETS ],
			"access_type" => "offline",
			"approval_prompt" => "auto"

		) );

		$client->setAccessType( 'offline' );

		return $client;

	}



	private function initializeClientWithAccessToken() {

		$accessToken = $this->getUserAccessToken();

		if ( ! $accessToken ) {

			throw new \Exception("No Access token found for this user");
			// redirect('/sheets/init');

		}

		$client = $this->getClient();

		$client->setAccessToken( $accessToken->key_value );
     

		if ( $client->isAccessTokenExpired() ) {

			if ( $accessToken->refresh_token != '' && $accessToken->refresh_token != 'Not available' ) {

				$new_access_token = $client->fetchAccessTokenWithRefreshToken( $accessToken->refresh_token );
                

                if(array_key_exists('access_token',$new_access_token)) {
    
                    $accessToken->key_value = $new_access_token['access_token'];
                    $accessToken->save();

                    $client->setAccessToken( $new_access_token['access_token'] );
                
                }

			} else {

               throw new \Exception("Access token expired, No refresh token available in database.");

            }

		}

		return $client;

	}

	public function readAllDataFromSheet( $sheet_id ) {

		try {
			$client = $this->initializeClientWithAccessToken();		
		} catch(\Exception $e) {
		
			throw new \Exception($e->getMessage());

			
		}

		// var_dump($client); die(); 

        $sheets = new Sheets($client);
        
        $sheet_data = $sheets->spreadsheets_values->get($sheet_id, "A1:Z1000");

        $extracted_data = $sheet_data->getValues();

        return $extracted_data;


	}







}