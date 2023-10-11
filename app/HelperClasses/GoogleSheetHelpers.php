<?php

namespace App\HelperClasses;

use Google\Client;
use Google\Service\Sheets;
class GoogleSheetHelpers {

	public $user;

	public function __construct( $user ) {

		$this->user = $user;


	}


	public function getUserAccessToken() {

		$user = $this->user;

		if ( ! $user ) {

			return false;
		}

		$accessTokens = $user->google_access_token()->where( [ 'auth_type' => 'google_oauth' ] );

		if ( $accessTokens->count() < 1 ) {

			return false;
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

			return false;
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

                return false;

            }

		}

		return $client;

	}

	public function readAllDataFromSheet( $sheet_id ) {

		$client = $this->initializeClientWithAccessToken();

        $sheets = new Sheets($client);
        
        $sheet_data = $sheets->spreadsheets_values->get($sheet_id, "A1:Z1000");

        $extracted_data = $sheet_data->getValues();

        return $extracted_data;


	}







}