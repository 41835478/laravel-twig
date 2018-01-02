<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class GeoController extends Controller
{

    public function country(){
        $list = DB::table("country")->get()->pluck("name","id");
        return response()->json(["status" => true, 'data' => $list]);
    }

    public function state(Request $request){
        $country_id = $request->input("country_id");
        if (empty($country_id)){
            return response()->json(["status" => false,"errors"=>"country_id is empty"]);
        }
        $data["has_state"] = true;
        $list = DB::table("areas")->where("parentId",$country_id)->orderby("areaEnName","asc")->get()->pluck("areaEnName","areaId");
        if ($list->isEmpty()){
            $data["has_state"] = false;
        }
        $data["data"] = $list;
        return response()->json(["status" => true, 'data' => $data]);
    }

    public function city(Request $request){
        $state_id = $request->input("state_id","");
        if (empty($state_id)){
            return response()->json(["status" => false,"errors"=>"state_id is empty"]);
        }
        $list = DB::table("city")->where("state_id",$state_id)->get()->pluck("name","id");
        return response()->json(["status" => true, 'data' => $list]);
    }

}
