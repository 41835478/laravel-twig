<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    public static function getCountryList(){
        $country_list = self::where("deep",2)->orderby("areaEnName","asc")->pluck("areaEnName","areaId");
        return $country_list;
    }

    public static function getProvince($country_id){
        $province_list = self::where("deep",3)->where("parentId",$country_id)->orderby("areaEnName","asc")->pluck("areaEnName","areaId");
        return $province_list;
    }




}
