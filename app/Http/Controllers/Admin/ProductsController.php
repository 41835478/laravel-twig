<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SuperBuyOpenApi;
use App\Http\Models\Products;
use App\Http\Models\MongoProducts;
use App\Http\Helper\Helper;
use App\Http\Models\CollectionProducts;

class ProductsController extends Controller
{

    function search(Request $request)
    {

        $keywords = $request->input("keywords");
        $to_page = $request->input("to_page", 1);
        $per_pagesize = $request->input("per_pagesize", 20);
        $free_shipment = $request->input("free_shipment", ""); // 1 免国内运费
        $start_price = $request->input("start_price", "");
        $end_price = $request->input("end_price", "");
        $platform = $request->input("platform"); // 1:淘宝;2:天 猫;3京东；10:淘宝联盟
        $lang_type = $request->input("lang_type", "");

        if (empty($keywords)) {
            return response()->json(["status" => false, "errors" => "keywords is empty"]);
        }
        $open_supbuy_client = new SuperBuyOpenApi();
        $query_params = ["keyword" => $keywords, "toPage" => $to_page, "perPageSize" => $per_pagesize];
        if (!empty($start_price)) {
            $query_params["startPrice"] = $start_price;
        }
        if (!empty($end_price)) {
            $query_params["endPrice"] = $end_price;
        }
        if (!empty($free_shipment)) {
            $query_params["freeShipment"] = $free_shipment;
        }
        if (!empty($platform_name)) {
            $query_params["platform"] = $platform;
        }
        if (!empty($lang_type)) {
            $query_params["langType"] = $lang_type;
        }
        try {
            $content = $open_supbuy_client->search($query_params);
            $return = json_decode($content, true);
            if ($return["state"] == 0) {
                return response()->json(["status" => true, "data" => $return["data"]]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Server busy Please try again later"]);
        }

    }


    function index(Request $request)
    {
        $per_pagesize = $request->input("per_pagesize", 1);
        $page = $request->input("page", 20);
        $business_id = $request->business_info->id;
        $params["business_id"] = $business_id;
        $offset = ($page - 1);
        if ($offset <= 0) {
            $offset = 0;
        }
        $params["product_name"] = $request->input("product_name", "");
        $params["up_and_down"] = $request->input("up_and_down", "");
        $data = Products::where(function ($query) use ($params) {
            if (!empty($params['product_name'])) {
                $query->where('product_name', "like", "%{$params["product_name"]}%");
            }
            $query->where("business_id", $params["business_id"]);
            if (!empty($params["up_and_down"])) {
                $query->where("up_and_down", $params["up_and_down"]);
            }
        })->orderby("id", "asc")->offset($offset)->paginate($per_pagesize);

        $result = [];
        foreach ($data as $key => $value) {
            $spu_imgs = !empty($value->spu_imgs) ? explode(";", $value->spu_imgs) : "";
            $result[$key]["product_id"] = $value->id;
            if (is_array($spu_imgs)) {
                $result[$key]["spu_img"] = $spu_imgs[0];
            } else {
                $result[$key]["spu_img"] = "";
            }
            $result[$key]["product_name"] = $value->product_name;
            $result[$key]["up_and_down"] = $value->up_and_down;
            $result[$key]["platform_name"] = $value->platform_name;
            if ($value->platform == 1) {
                $result[$key]["source_url"] = "https://item.taobao.com/item.htm?id=" . $value->spu_code;
            } elseif ($value->platform == 2) {
                $result[$key]["source_url"] = "https://detail.tmall.com/item.htm?id=" . $value->spu_code;
            } elseif ($value->platform == 3) {
                $result[$key]["source_url"] = "https://item.jd.com/{$value->spu_code}.html";
            }
        }
        $return = Helper::pageFormat($data);
        $return["data"] = $result;
        return response()->json(["status" => true, "data" => $return]);

    }

    /**
     * 推到我的店铺中
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function pushStore(Request $request)
    {
        $goods_id = $request->input("goods_id");
        $platform_name = $request->input("provider_type"); // TB TMALL
        $language = $request->input("language", 1);
        if (empty($goods_id) || empty($platform_name)) {
            return response()->json(["status" => false, "errors" => "Required parameter is blank"]);
        }
        // 检验产品是否增加过
        $business_id = $request->business_info->id;
        $platform = 0;
        switch ($platform_name) {
            case "TB":
                $platform = 1;
                break;
            case "TMALL":
                $platform = 2;
                break;
            case "JD":
                $platform = 3;
                break;
        }
        $check_push = Products::where("business_id", $business_id)->where("spu_code", $goods_id)->where("platform", $platform)->value("id");
        if (!empty($check_push)) {
            return response()->json(["status" => false, "errors" => "This product has been added to the store"]);
        }
        $open_supbuy_client = new SuperBuyOpenApi();
        $query_params = ["spuCode" => $goods_id, "platform" => $platform_name, "needTimelyGrab" => 1, "language" => $language];
        try {
            $data = [];
            $content = $open_supbuy_client->getGoodInfo($query_params);
            $content = json_decode($content, true);
            $result = $content["data"];

            $data["business_id"] = $business_id;
            $data["platform"] = $platform;
            $data["platform_name"] = $platform_name;
            $data["spu_code"] = $result["spuCode"];
            $data["product_name"] = isset($result["productName"]) ? $result["productName"] : "";
            $data["product_title"] = isset($result["productTitle"]) ? $result["productTitle"] : "";
            $data["spu_imgs"] = isset($result["spuImgs"]) ? implode(";", $result["spuImgs"]) : "";
            $data["up_and_down"] = isset($result["upAndDown"]) ? $result["upAndDown"] : 1;
            $data["update_time"] = isset($result["updateTime"]) ? date("Y-m-d H:i:s", substr($result["updateTime"], 0, 10)) : date("Y-m-d H:i:s");
            $data["created_at"] = date("Y-m-d H:i:s");
            $data["updated_at"] = date("Y-m-d H:i:s");
            $skus = isset($result["skus"])?$result["skus"]:"";
            if(!empty($skus)){
                $data["price"] = isset($skus[0])?$skus[0]["sellPrice"]/100:0;
            }
            $product_id = Products::insertGetId($data);
            $result["product_id"] = $product_id;
            $result["business_id"] = $business_id;

            MongoProducts::insert($result);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Add to store failed Please try again later"]);
        }
        return response()->json(["status" => true, "msg" => "Push to store success"]);
    }

    public function action(Request $request)
    {

        $type = $request->input("type", "");
        if (empty($type)) {
            return response()->json(["status" => false, "errors" => "The type of operation is empty"]);
        }
        $product_id_list = $request->input("product_id_list");
        if (!is_array($product_id_list)) {
            return response()->json(["status" => false, "errors" => "product_id_list Parameter error"]);
        }
        $business_id = $request->business_info->id;
        $msg = "action success";
        try {
            if ($type == 1) {
                Products::where("business_id", $business_id)->whereIn("id", $product_id_list)->update(["up_and_down" => 1]);
                $msg = "Shelves success";
            } elseif ($type == 2) {
                Products::where("business_id", $business_id)->whereIn("id", $product_id_list)->update(["up_and_down" => -1]);
                $msg = "Under the shelf success";
            } elseif ($type == 3) {
                Products::where("business_id", $business_id)->whereIn("id", $product_id_list)->delete();
                //删除在所有的分类信息
                CollectionProducts::whereIn("product_id",$product_id_list)->delete();
                $msg = "successfully deleted";
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Operation failed Please try again later"]);
        }
        return response()->json(["status" => true, "msg" => $msg]);
    }


}
