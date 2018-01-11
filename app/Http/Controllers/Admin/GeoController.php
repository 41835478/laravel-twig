<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Models\Countrys ;

class GeoController extends Controller
{

    public function country(){
        $list = Countrys::getList();
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



}
