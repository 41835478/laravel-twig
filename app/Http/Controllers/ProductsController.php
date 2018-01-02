<?php

namespace App\Http\Controllers;

use App\Http\Models\CollectionProducts;
use App\Http\Models\Collections;
use Illuminate\Http\Request;
use App\Http\Models\Products;
use App\Http\Models\MongoProducts;
use App\Http\Helper\Helper;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }


    function show(Request $request, $id)
    {
        $product = Products::where("id", $id)->first();
        if ($product->spu_imgs) {
            $product->spu_imgs = explode(";", $product->spu_imgs);
        }
        $business_info = $request->session()->get("business_info");
        $business_id = $business_info["id"];
        //找到所属的第一个分类
        $collection_id = CollectionProducts::where("product_id", $product->id)->value("collection_id");
        $product_list = CollectionProducts::where("collection_id", $collection_id)->where("product_id", "!=", $id)->limit(4)->pluck("product_id", "id");
        $collection_product_list = [];
        if (!empty($product_list)) {
            $product_id_list = $product_list->toArray();
            $product_list = Products::whereIn("id", $product_id_list)->select("id", "platform_name", "spu_code", "spu_imgs", "product_name")->get();
            foreach ($product_list as $key => $val) {
                $spu_imgs = !empty($val->spu_imgs) ? explode(";", $val->spu_imgs) : "";
                if (is_array($spu_imgs)) {
                    $spu_img = $spu_imgs[0];
                } else {
                    $spu_img = "";
                }
                $collection_product_list[$key]["collection_id"] = $collection_id;
                $collection_product_list[$key]["id"] = $val->id;
                $collection_product_list[$key]["spu_img"] = $spu_img;
                $collection_product_list[$key]["product_name"] = $val->product_name;
            }
        }
        $product->collection_product_list = $collection_product_list;
        // 获取spu 销售信息
        $product_sku_info = MongoProducts::where("business_id", $business_id)->where("product_id", intval($id))->select("skus", "productProps")->first();
        if (!empty($product_sku_info)) {
            $product_sku_info = $product_sku_info->toArray();
            $productProps = Helper::skuOptionFormat($product_sku_info["productProps"]);
            $product_sku_info["productProps"] = $productProps;
            $skus = Helper::skuFormat($product_sku_info["skus"]);
            $product_sku_info["skus"] = $skus;
        }
        $product["url"] = $request->server("HTTP_HOST") . "/products/$id";
        return view("product_info", ["product_info" => $product, "product_sku_info" => $product_sku_info]);
    }

    function collectionProductInfo(Request $request, $collection_id, $product_id)
    {
        $product = Products::where("id", $product_id)->first();
        if ($product->spu_imgs) {
            $product->spu_imgs = explode(";", $product->spu_imgs);
        }
        $business_info = $request->session()->get("business_info");
        $business_id = $business_info["id"];
        //找到所属的第一个分类
        $product_list = CollectionProducts::where("collection_id", $collection_id)->where("product_id", "!=", $product_id)->limit(4)->pluck("product_id", "id");
        $collection_product_list = [];
        if (!empty($product_list)) {
            $product_id_list = $product_list->toArray();
            $product_list = Products::whereIn("id", $product_id_list)->select("id", "platform_name", "spu_code", "spu_imgs", "product_name")->get();
            foreach ($product_list as $key => $val) {
                $spu_imgs = !empty($val->spu_imgs) ? explode(";", $val->spu_imgs) : "";
                if (is_array($spu_imgs)) {
                    $spu_img = $spu_imgs[0];
                } else {
                    $spu_img = "";
                }
                $collection_product_list[$key]["collection_id"] = $collection_id;
                $collection_product_list[$key]["id"] = $val->id;
                $collection_product_list[$key]["spu_img"] = $spu_img;
                $collection_product_list[$key]["product_name"] = $val->product_name;
            }
        }
        $product->collection_product_list = $collection_product_list;
        // 获取spu 销售信息
        $product_sku_info = MongoProducts::where("business_id", $business_id)->where("product_id", intval($product_id))->select("skus", "productProps")->first();
        if (!empty($product_sku_info)) {
            $product_sku_info = $product_sku_info->toArray();
            $productProps = Helper::skuOptionFormat($product_sku_info["productProps"]);
            $product_sku_info["productProps"] = $productProps;
            $skus = Helper::skuFormat($product_sku_info["skus"]);
            $product_sku_info["skus"] = $skus;
        }
        $product["collection_url"] = "/collections/$collection_id";
        $product["collection_title"] = Collections::where("id", $collection_id)->where("business_id", $business_id)->value("title");
        $product["url"] = $request->server("HTTP_HOST") . "/collections/{$collection_id}/products/$product_id";

        return view("product_info", ["product_info" => $product, "product_sku_info" => $product_sku_info]);
    }




}
