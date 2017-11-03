<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Log;

class QBDataServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('QuickBooksOnline\API\DataService\DataService', function ($app) {
            $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
                     'auth_mode' => 'oauth2',
                     'ClientID' => env('QB_ClientID'),
                     'ClientSecret' => env('QB_ClientSecret'),
                     'accessTokenKey' => env('QB_accessTokenKey'),
                     'refreshTokenKey' => env('QB_refreshTokenKey'),
                     'QBORealmID' => env('QB_RealmID'),

                     //For Development Keys, use URL "https://sandbox-quickbooks.api.intuit.com/" or the "Development" keyword as baseUrl
                     //For Production Keys, use URL "https://quickbooks.api.intuit.com/" or the "Production" keyword as baseUrl.
                     'baseUrl' => env('APP_ENV')=='production'?"Production":"Development"
            ));
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $accessToken = $OAuth2LoginHelper->refreshToken();
            $error = $OAuth2LoginHelper->getLastError();
            if ($error != null) {
                $errormessage = "The Status code is: " . $error->getHttpStatusCode() . "\n";
                $errormessage .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                $errormessage .= "The Response message is: " . $error->getResponseBody() . "\n";
                Log::error($errormessage);
            }
            
            $dataService->updateOAuth2Token($accessToken);
            return $dataService;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['QuickBooksOnline\API\DataService\DataService:class'];
    }
}
