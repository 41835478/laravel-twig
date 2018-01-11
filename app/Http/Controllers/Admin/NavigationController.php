<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Navigation;
use Illuminate\Http\Response;


class NavigationController extends Controller
{

    function index(Request $request){
        $business_id = $request->business_info->id;
        $list = Navigation::where("navigation.business_id",$business_id)->leftJoin("menus","navigation.id","=","menus.navigation_id")->select("navigation.id as navigation_id","navigation.title as navigation_title","navigation.handle","menus.title")->get();
        $content = [];
        if(!$list->isEmpty()){
            foreach ($list as $key=>$value){

              if(!array_key_exists($value->navigation_id,$content)){
                  $content[$value->navigation_id] = ["navigation_id"=>$value->navigation_id,"navigation_title"=>$value->navigation_title,"handle"=>$value->handle,"menu_list"=>[]];
              }
              if(!empty($value->title)){
                  array_push($content[$value->navigation_id]["menu_list"],$value->title);
              }
            }
        }
        $content = array_values($content);
        return Response()->json(["status"=>true,"data"=>$content]);
    }


}
