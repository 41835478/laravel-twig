<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Models\Areas;

use DB;


class AccountsAddress extends Model
{
    public $table = "accounts_address";


    public static function setDefault($account_id, $default_address_id)
    {
        return self::where("account_id", $account_id)->where("id", "!=", $default_address_id)->update(["default" => 0]);
    }

    public static function getAddress($account_id)
    {
        $address_list = self::where("account_id", $account_id)->get();
        if ($address_list->isNotEmpty()) {
            $address_list = $address_list->toArray();
        } else {
            $address_list = [];
        }
        //遍历出 已经选择国家的省市列表
        if (!empty($address_list)) {
            foreach ($address_list as $key => $value) {
                if (isset($value["country_id"]) && !empty($value["country_id"])) {
                    $country_id = $value["country_id"];
                } else {
                    //默认美国
                    $country_id = 3;
                }
                $province_list = Areas::getProvince($country_id);
                $address_list[$key]["province_list"] = $province_list;
            }
        }
        return $address_list;
    }

    public static function getAddressInfo($address_id){
        return self::where("id",$address_id)->first();
    }


  public static function formatData($account_id,$address){

      $data["account_id"] = $account_id ;
      $data["first_name"] = $address["first_name"];
      $data["last_name"] = $address["last_name"];
      $data["company"] = $address["company"];
      $data["address1"] = $address["address1"];
      $data["address2"] = $address["address2"];
      $data["city"] = $address["city"];

      // 只存到 国家 省
      $data["country_id"] = $address["country"];
      $country_name=DB::table("areas")->where("areaId",$address["country"])->value("areaEnName");
      $data["country_name"] = $country_name;

      if(isset($address["province"])){
          $data["province_id"] = $address["province"];
          $province_name =DB::table("areas")->where("areaId",$address["province"])->value("areaEnName");
          $data["province_name"] = $province_name;
      }

      $data["zip_code"] = $address["zip"];
      $data["phone_number"] = $address["phone"];
      if(isset($address["default"])){
          $data["default"] = $address["default"];
      }
      $date_time = date("Y-m-d H:i:s");
      $data["updated_at"] = $date_time;

      return $data;
  }


}
