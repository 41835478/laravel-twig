<?php
/**
 * Created by PhpStorm.
 * User: river
 * Date: 2017/12/13
 * Time: 20:34
 */

namespace App\Services;

use App\Services\Network;

class SuperBuyOpenApi
{
    public $app_id = "0000000345154401";
    public $secret = "";
    public $open_url = "http://opentest.superbuy.com";


    public function search($query_params)
    {
        $access_token = $this->getAccessToken();
        $search_url = $this->open_url . "/rest/v1/product/productpage?access_token=$access_token";
        $header = array('Content-Type: application/x-www-form-urlencoded');
        $protocol  =strpos($this->open_url,"https") == false ? "http":"https";
        $content = Network::makeRequest($search_url, $query_params, 20,"post",$protocol,$header);
        if ($content["result"] == true) {
            return $content["msg"];
        }
        return false;
    }

    public function getGoodInfo($query_params){
        $access_token = $this->getAccessToken();
        $good_info_url = $this->open_url . "/rest/v1/uo/business/product/productInfo?access_token=$access_token";
        $header = array('Content-Type: application/json');
        $protocol  =strpos($this->open_url,"https") == false ? "http":"https";
        $content = Network::makeRequest($good_info_url, json_encode($query_params), 20,"post",$protocol,$header);
        if ($content["result"] == true) {
            return $content["msg"];
        }
        return false;
    }

    public function getAccessToken()
    {
        $auth_url = $this->open_url . "/rest/v1/auth/token";
        $query_params = ["appId" => $this->app_id, "sign" => $this->getSign(), "t" => $this->getMillisecond()];

        $content = Network::makeRequest($auth_url, $query_params, 20,  "get");
        if ($content["result"] == true) {
            $msg = json_decode($content["msg"], true);
            if (isset($msg["data"]["token"])) {
                return $msg["data"]["token"];
            }
        }
    }


    function getSign()
    {
        return "5013304f60510d9621cbe220434ae6e8";
        $sign = md5($this->app_id . $this->getMillisecond() . $this->secret);
        return $sign;
    }

    function getMillisecond()
    {
        return "1500447101159";
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);

    }

    public static  function getRateQuote($query_params){
        $http_url = "http://testapi.oa.com/logistics/routes/rate-quote";
        $header = array('Content-Type: application/json');
        $protocol  =strpos($http_url,"https") == false ? "http":"https";
        $content = Network::makeRequest($http_url, json_encode($query_params), 20,"post",$protocol,$header);
        if ($content["result"] == true) {
            return $content["msg"];
        }
        return false;
    }


}