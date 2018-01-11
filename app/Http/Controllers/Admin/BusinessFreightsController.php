<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SuperBuyOpenApi;
use App\Http\Models\Countrys;
use App\Http\Models\BusinessFreights;
use App\Http\Models\BusinessSettings ;
use App\Http\Helper\Helper ;

class BusinessFreightsController extends Controller
{

    function index(Request $request)
    {
        $business_id = $request->business_info->id;
        $list = BusinessFreights::where("business_id",$business_id)->select("country_id","country_name","shipping_methods_name")->get();
        $content = [];
        if(!$list->isEmpty()){
            foreach ($list as $key=>$value){
                if(!array_key_exists($value->country_id,$content)){
                    $content[$value->country_id] = ["country_name"=>$value->country_name,"country_id"=>$value->country_id,"name_list"=>[]];
                }
               array_push($content[$value->country_id]["name_list"],$value->shipping_methods_name);
            }
        }
        $content = array_values($content);
        return Response()->json(["status"=>true,"data"=>$content]);
    }

    /**
     * 暂时废弃
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountryList(Request $request)
    {
        $business_id = $request->business_info->id;
        $country_list = Countrys::getList();
        $config_country_list = BusinessFreights::where("business_id", $business_id)->pluck("country_id", "id");
        $result = [];
        foreach ($country_list as $key => $value) {
            $result[] = ["country_id" => $key, "country_name" => $value, "available" => 1];
        }
        return Response()->json(["status" => true, "data" => $result]);
    }


    public function getRateQuote(Request $request)
    {
        $country_id = $request->input("country_id");
        if (empty($country_id)) {
            return Response()->json(["status" => false, "errors" => "country_id is empty"]);
        }
        $rate_data = ["weight" => 500, "length" => 0, "height" => 0, "width" => 0, "destination" => $country_id, "warehouse" => 4];
        $open_supbuy_client = new SuperBuyOpenApi();

        $rate_list = $open_supbuy_client->getRateQuote($rate_data);

        $rate_list = json_decode($rate_list, true);
        $rate = [];
        if (isset($rate_list["state"]) && intval($rate_list["state"]) == 0) {
            $rate = $rate_list["data"];
        }
        foreach ($rate as $key => $value) {
            if ($value["available"] == false) {
                unset($rate[$key]);
            }
        }
        $business_id = $request->business_info->id;
        $currency = BusinessSettings::where("business_id",$business_id)->value("currency");
        $result = [];
        $rate_currency = Helper::Rate($currency);
        foreach ($rate as $key => $value) {
            $basic_freight_rate = round(($value["basicFreightRate"]/100)*$rate_currency,2) ;
            $additional_freight_rate = round(($value["additionalFreightRate"]/100)*$rate_currency,2) ;
            $result[] = [
                "product_id" => $value["productId"], "product_name" => $value["productEnName"], "basic_weight" => $value["basicWeight"], "basic_freight_rate" => $basic_freight_rate,
                "additional_weight" => $value["additionalWeight"], "additional_freight_rate" => $additional_freight_rate, "currency" => "$currency"
            ];
        }
        return Response()->json(["status" => true, "data" => $result]);
    }


    public function store(Request $request)
    {
        $country_list = $request->input("country_list");

        if (empty($country_list)) {
            return response()->json(["status" => false, "errors" => "country_list can't be blank"]);
        }
        $free = $request->input("free");
        $business_id = $request->business_info->id;
        $date_time = date("Y-m-d H:i:s");
        $result = [];
        $shipping = $request->input("shipping");
        $shipping_methods_id = $shipping["product_id"];


        if (empty($free)) {
            $shipping_methods_name = $shipping["product_name"];
            $basic_weight = $shipping["basic_weight"];
            $basic_freight_rate = $shipping["basic_freight_rate"];
            $additional_weight = $shipping["additional_weight"];
            $additional_weight_rate = $shipping["additional_weight_rate"];

            foreach ($country_list as $value) {
                foreach ($shipping_methods_id as $k => $v) {
                    //检查是否有重复的
                    $ckeck_id = BusinessFreights::where("business_id", $business_id)->where("country_id", $value)->where("shipping_methods_id", $v)->value("id");
                    $data = [
                        "business_id" => $business_id, "country_id" => $value, "country_name" => "", "shipping_methods_id" => $v,
                        "shipping_methods_name" => $shipping_methods_name[$k],
                        "basic_weight" => $basic_weight[$k],
                        "basic_freight_rate" => $basic_freight_rate[$k],
                        "additional_weight" => $additional_weight[$k],
                        "additional_weight_rate" => $additional_weight_rate[$k]
                    ];
                    if (empty($ckeck_id)) {
                        //新增的
                        $data["created_at"] = $date_time;
                        $data["updated_at"] = $date_time;
                        $result[] = $data;
                    } else {
                        //直接更新
                        $data["updated_at"] = $date_time;
                        BusinessFreights::where("id", $ckeck_id)->update($data);
                    }
                }
            }
        } else {
            //免邮
            foreach ($country_list as $value) {
                foreach ($shipping_methods_id as $k => $v) {
                    $ckeck_id = BusinessFreights::where("business_id", $business_id)->where("country_id", $value)->where("shipping_methods_id", $v)->value("id");
                    $data = [
                        "business_id" => $business_id, "country_id" => $value, "country_name" => "","shipping_methods_id"=>$v,
                        "free" => 1
                    ];
                    if (empty($ckeck_id)) {
                        //新增的
                        $data["created_at"] = $date_time;
                        $data["updated_at"] = $date_time;
                        $result[] = $data;
                    } else {
                        //直接更新
                        $data["updated_at"] = $date_time;
                        BusinessFreights::where("id", $ckeck_id)->update($data);
                    }
                }
            }
        }
        try {
            if (!empty($result)) {
                $msg = "Added successfully";
                BusinessFreights::insert($result);
            } else {
                $msg = "updated successfully";
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "The server encountered an error. Please try again later"]);
        }
        return response()->json(["status" => true, "msg" => $msg]);
    }

    //单个国家的物流信息
    public function show(Request $request,$country_id)
    {

        $business_id = $request->business_info->id;
        $list = BusinessFreights::where("business_id",$business_id)->where("country_id",$country_id)->select("country_id","country_name","shipping_methods_id as product_id","shipping_methods_name as product_name",
            "basic_weight","basic_freight_rate","additional_weight","additional_weight_rate")->get();

        return Response()->json(["status" => true, "data" => $list]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function action(Request $request)
    {
        $type = $request->input("type", "");
        if (empty($type)) {
            return response()->json(["status" => false, "errors" => "The type of operation is empty"]);
        }
        $country_id_list = $request->input("country_id_list");
        if (!is_array($country_id_list)) {
            return response()->json(["status" => false, "errors" => "collection_id_list Parameter error"]);
        }
        $business_id = $request->business_info->id;
        $msg = "action success";
        try {
           if ($type == 3) {
               BusinessFreights::where("business_id", $business_id)->whereIn("country_id", $country_id_list)->delete();
                $msg = "successfully deleted";
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Operation failed Please try again later"]);
        }
        return response()->json(["status" => true, "msg" => $msg]);
    }



}
