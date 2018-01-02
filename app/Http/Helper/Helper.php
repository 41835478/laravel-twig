<?php
/**
 * Created by PhpStorm.
 * User: river
 * Date: 2017/12/15
 * Time: 10:52
 */

namespace App\Http\Helper;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Illuminate\Support\Facades\Session;


class Helper
{
    public static $_log = [];

    public static function pageFormat($data)
    {
        $result = $data->toArray();
        unset($result["first_page_url"]);
        unset($result["last_page_url"]);
        unset($result["next_page_url"]);
        unset($result["path"]);
        unset($result["prev_page_url"]);
        $result["per_pagesize"] = intval($result["per_page"]);
        $result["total_page"] = ceil($result["total"] / $result["per_page"]);
        unset($result["from"]);
        unset($result["last_page"]);
        unset($result["to"]);
        unset($result["per_page"]);
        return $result;
    }


    public static function skuOptionFormat($productProps)
    {
//        dd($productProps);
        $result = [];
        if (is_array($productProps) && empty($productProps)) {
            return $result;
        }
        $first_prop_id = "";
        $data = [];

        foreach ($productProps as $key => $value) {
            if ($key == 0) {
                $first_prop_id = $value["propId"];
                $data = ["propId" => $first_prop_id, "propName" => $value["propName"]];
            }
            if ($first_prop_id == $value["propId"]) {
                $data["data"][] = ["valueId" => $value["valueId"], "valueName" => $value["valueName"], "imgUrl" => $value["imgUrl"]];

            } else {
                $result[] = $data;
                $first_prop_id = $value["propId"];
                $data = ["propId" => $first_prop_id, "propName" => $value["propName"]];
                $data["data"][] = ["valueId" => $value["valueId"], "valueName" => $value["valueName"], "imgUrl" => $value["imgUrl"]];
            }
        }
        $result[] = $data;
        return $result;
    }


    public static function skuFormat($skuProps)
    {
        $result = [];
        if (is_array($skuProps) && empty($skuProps)) {
            return $result;
        }
        foreach ($skuProps as $value) {
            $data = [];
            if ($value["productProps"] && !empty($value["productProps"])) {
                $value_id = [];
                foreach ($value["productProps"] as $v) {
                    $valueId = $v["valueId"];
                    $value_id[] = $valueId;
                }
                $data["sellPrice"] = $value["sellPrice"] / 100;
                $data["sellableNum"] = $value["sellableNum"];
                $value_id_key = implode("_", $value_id);
                $data["skuCode"] = $value["skuCode"];
                $result[$value_id_key] = $data;
            }
        }
        return $result;
    }


    public static function ProductUrlFormat($product_id)
    {
        return "/products/$product_id";
    }

    public static function getLog($cmd)
    {
        if (!isset(self::$_log[$cmd])) {
            self::$_log[$cmd] = new Logger('api');
            $file_name = date("Y-m-d") . ".log";
            $log_dir = storage_path("logs") . "/{$cmd}";
            self::$_log[$cmd]->pushHandler(new StreamHandler("$log_dir/$file_name"));
            return self::$_log[$cmd];
        }
        return self::$_log[$cmd];
    }




}