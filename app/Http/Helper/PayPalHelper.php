<?php
/*
 * Sample bootstrap file.
 */

namespace App\Http\Helper;


use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

Class PayPalHelper
{


// Replace these values by entering your own ClientId and Secret by visiting https://developer.paypal.com/developer/applications/
    public static $clientId = 'AaM3Zm4S3UZkLd0MjA5R3wIwURiSApB5n_DclwYmigC_RVmZFz3QlMGeoTphY0Fr1CX1L3Lh1pZaGu_5';
    public static $clientSecret = 'ECZTeppO_DEimNqtrheEpvyy3AcVA8uDalrRNBPMfZSCgOpR8wM4HBsbXm-Q77YGvAFwZpZocczdm1qn';

    /**
     * All default curl options are stored in the array inside the PayPalHttpConfig class. To make changes to those settings
     * for your specific environments, feel free to add them using the code shown below
     * Uncomment below line to override any default curl options.
     */
// \PayPal\Core\PayPalHttpConfig::$defaultCurlOptions[CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;

    public static  function getApiContext()
    {

        // #### SDK configuration
        // Register the sdk_config.ini file in current directory
        // as the configuration source.
        /*
        if(!defined("PP_CONFIG_PATH")) {
            define("PP_CONFIG_PATH", __DIR__);
        }
        */
        // ### Api context
        // Use an ApiContext object to authenticate
        // API calls. The clientId and clientSecret for the
        // OAuthTokenCredential class can be retrieved from
        // developer.paypal.com

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                self::$clientId,
                self::$clientSecret
            )
        );
        // Comment this line out and uncomment the PP_CONFIG_PATH
        // 'define' block if you want to use static file
        // based configuration
        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'log.LogEnabled' => true,
                'log.FileName' => '../PayPal.log',
                'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'cache.enabled' => true,
                // 'http.CURLOPT_CONNECTTIMEOUT' => 30
                // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
                //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
            )
        );
        // Partner Attribution Id
        // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
        // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
        // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');
        return $apiContext;
    }


}

