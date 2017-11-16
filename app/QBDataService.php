<?php namespace App;

use QuickBooksOnline\API\DataService\DataService;
use Log;

class QBDataService extends DataService {

    public static function Configure($connection = 'quickbooks1')
    {
        $dataservice = parent::Configure(array(
                 'auth_mode' => 'oauth2',
                 'ClientID' => config('quickbooks.'.$connection.'.client_id'),
                 'ClientSecret' => config('quickbooks.'.$connection.'.client_secret'),
                 'accessTokenKey' => config('quickbooks.'.$connection.'.access_token'),
                 'refreshTokenKey' => config('quickbooks.'.$connection.'.refresh_token'),
                 'QBORealmID' => config('quickbooks.'.$connection.'.realm_id'),

                 //For Development Keys, use URL "https://sandbox-quickbooks.api.intuit.com/" or the "Development" keyword as baseUrl
                 //For Production Keys, use URL "https://quickbooks.api.intuit.com/" or the "Production" keyword as baseUrl.
                 'baseUrl' => env('APP_ENV')=='production'?"Production":"Development"
        ));
        $OAuth2LoginHelper = $dataservice->getOAuth2LoginHelper();
        $accessToken = $OAuth2LoginHelper->refreshToken();
        $error = $OAuth2LoginHelper->getLastError();
        if ($error != null) {
            $errormessage = "The Status code is: " . $error->getHttpStatusCode() . "\n";
            $errormessage .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            $errormessage .= "The Response message is: " . $error->getResponseBody() . "\n";
            Log::error($errormessage);
        }
        
        $dataservice->updateOAuth2Token($accessToken);
        return $dataservice;
    }
            
}