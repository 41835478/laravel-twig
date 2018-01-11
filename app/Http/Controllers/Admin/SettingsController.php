<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Currency;
use App\Http\Models\BusinessSettings;

/**
 * 店铺基本设置 包括货币 paypal 收款email
 * Class SettingsController
 * @package App\Http\Controllers\Admin
 */

class SettingsController extends Controller
{
    public function getCurrency(){

        $currency_list = Currency::where([])->select("area_id","area_en_name","currency","sign")->get();
        $currency = [];
        if(!$currency_list->isEmpty()){
            $currency = $currency_list->toArray();
            foreach ($currency as $key=>$value){
                $value["currency_format"] = "{$value["sign"]} {{amount}} {$value["currency"]}" ;
                unset($value["sign"]);
                $currency[$key] = $value;
            }
        }
        return Response()->json(["status" => true, "data" => $currency]);
    }

    public function show(Request $request){
        $business_id = $request->business_info->id;
        $settings =BusinessSettings::where("business_id",$business_id)->select("area_id","currency","currency_format","paypal_email")->first();
        if(empty($settings)){
            $settings = ["business_id"=>$business_id,"area_id"=>3,"currency"=>"USD","currency_format"=>"$ {{amount}} USD","paypal_email"=>"","created_at"=>date("Y-m-d H:i:s")];
            BusinessSettings::insert($settings);
            unset($settings["business_id"]);
            unset($settings["created_at"]);
        }
        return Response()->json(["status" => true, "data" => $settings]);
    }

    public function update(Request $request){
        $business_id = $request->business_info->id;
        $areaId = $request->input("area_id");
        $currency = $request->input("currency");
        $currency_format = $request->input("currency_format");
        $paypal_email = $request->input("paypal_email");
        $data = [];
        if(!empty($areaId) || !empty($currency) || !empty($currency_format)){
            $data["area_id"] = $areaId;
            $data["currency"] = $currency;
            $data["currency_format"] = $currency_format;
        }
        if(!empty($paypal_email)){
            $data["paypal_email"] = $paypal_email;
        }
        if(empty($data)){
            return Response()->json(["status" => false, "errors" => "Necessary parameters can not be empty"]);
        }
        BusinessSettings::where("business_id",$business_id)->update($data);
        return Response()->json(["status" => true, "data" => $data,"msg"=>"Saved successfully"]);
    }


}
