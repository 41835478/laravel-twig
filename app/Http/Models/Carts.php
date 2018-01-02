<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Models\MongoProducts;
use App\Http\Helper\Helper;
use DB;

class Carts extends Model
{

  public static function FormatProduct($carts,$business_id){
        $result = [];
        $total_price = 0;
        if (!empty($carts)) {
            foreach ($carts as $key => $value) {
                $product_info = MongoProducts::where("business_id", $business_id)->where("product_id", intval($value["product_id"]))->select("productName", "spuImgs", "skus")->first();
                $productProps = [];
                $spu_img = "";
                $price = 0;
                if (!empty($product_info)) {
                    $product_info = $product_info->toArray();
                    $skus = $product_info["skus"];
                    foreach ($skus as $v) {
                        if ($v["skuCode"] == $value["spuCode"]) {
                            $productProps = $v["productProps"];
                            $spu_img = $v["imgUrl"];
                            $price = $v["sellPrice"];
                            unset($product_info["skus"]);
                            continue;
                        }
                    }
                    if (empty($spu_img)) {
                        $spu_imgs = $product_info["spuImgs"];
                        $spu_img = array_first($spu_imgs);
                        unset($product_info["spuImgs"]);
                    }
                    $product_info["id"] = $key;
                    $product_info["productProps"] = $productProps;
                    $product_info["spu_img"] = $spu_img;
                    $product_info["quantity"] = intval($value["quantity"]);
                    $product_info["price"] = sprintf("%.2f", $price / 100);
                    $product_info["total_price"] = sprintf("%.2f", $product_info["quantity"] * $product_info["price"]);
                    $product_info["url"] = Helper::ProductUrlFormat($value["product_id"]);
                    $total_price += $product_info["total_price"];
                    $result[$key] = $product_info;
                }
            }
        }
        return [$result,$total_price];
    }


    /**
     * 一次同步用户session 购物车数据到 数据库中
     * @param $account_id
     * @param $business_id
     * @param $carts
     * @return bool
     */
    public static function addToCart($account_id,$business_id,$carts){
        DB::beginTransaction();
        try{
            $data = [];
            if(!empty($carts)){
                self::where("account_id",$account_id)->where("business_id",$business_id)->delete();
                $date_time = date("Y-m-d H:i:s");
                foreach ($carts as $key=>$value){
                    $data[]=["account_id"=>$account_id,"business_id"=>$business_id,"product_id"=>$value["product_id"],"spuCode"=>$value["spuCode"],"quantity"=>$value["quantity"],"created_at"=>$date_time,"updated_at"=>$date_time];
                }
            }
            if(!empty($data)){
                self::insert($data);
            }
        }catch (\Exception $e){
            DB::rollBack();
        }
        DB::commit();
        return true;
    }

    public static function updateToCart($account_id,$business_id,$product_id,$spu_code,$action,$quantity=0){
        if($action == "remove"){
            self::where("account_id",$account_id)->where("business_id",$business_id)->where("product_id",$product_id)->where("spuCode",$spu_code)->delete();
        }
        elseif ($action == "update_quantity"){
            self::where("account_id",$account_id)->where("business_id",$business_id)->where("product_id",$product_id)->where("spuCode",$spu_code)->update(["quantity"=>$quantity]);
        }
        return true;
    }

    public static function getCarts($account_id,$business_id){
        $result = self::where("account_id",$account_id)->where("business_id",$business_id)->get();
        $data = [];
        if(!$result->isEmpty()){
            foreach ($result as $key=>$value){
                $data[$value->product_id."_".$value->spuCode] = ["product_id"=>$value->product_id,"spuCode"=>$value["spuCode"],"quantity"=>$value["quantity"],"business_id"=>$business_id];
            }
        }
        return $data;
    }




}
