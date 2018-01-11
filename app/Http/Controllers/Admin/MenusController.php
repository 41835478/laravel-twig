<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Menus;
use App\Http\Models\Navigation;
use App\Http\Helper\Helper;


class MenusController extends Controller
{

    public function index(Request $request)
    {
        $navigation_id = $request->input("navigation_id");
        if (empty($navigation_id)) {
            Response()->json(["status" => false, "errors" => "navigation_id empty "]);
        }
        $business_id = $request->business_info->id;
        $navigation_info = Navigation::where("id", $navigation_id)->select("id as navigation_id", "title as navigation_title", "handle")->first();
        $menu_list = Menus::where("navigation_id", $navigation_id)->where("business_id", $business_id)->select("id", "title", "menu_type", "subject_id", "position", "parent_menu_id")->get();
        $result = [];
        if (!$menu_list->isEmpty()) {
            $result = $navigation_info->toArray();
            $result["menu_list"] = $menu_list->toArray();
        }
        return Response()->json(["status" => true, "data" => $result]);
    }
    
    public function linkList()
    {
        //连接的列表
        $result = [
            ["title" => "home", "menu_type" => "home","subclass"=>false],
            ["title" => "search", "menu_type" => "search","subclass"=>false],
            ["title" => "collection", "menu_type" => "collection", "subclass"=>true,"menu_list" => ["collection_id" => -1, "title" => "ALL Collections"]],
            ["title" => "product", "menu_type" => "product", "subclass"=>true,"menu_list" => ["product_id" => -1, "product_name" => "All Product"]],
            ["title" => "page", "menu_type" => "page","subclass"=>true],
            ["title" => "blog", "menu_type" => "blog","subclass"=>true]
        ];
        return Response()->json(["status" => true, "data" => $result]);
    }


    public function store(Request $request)
    {
        $title = $request->input("title");
        if (empty($title)) {
            return response()->json(["status" => false, "errors" => "Title can't be blank"]);
        }
        $navigation_id = $request->input("navigation_id");
        if (empty($navigation_id)) {
            return Response()->json(["status" => false, "errors" => "navigation_id empty "]);
        }
        $menu_type = $request->input("menu_type");
        $subject_id = $request->input("subject_id",-1);
        if (empty($menu_type)) {
            return Response()->json(["status" => false, "errors" => " Parameter is empty"]);
        }
        $position = $request->input("position", 1);
        $parent_menu_id = $request->input("parent_menu_id", 0); // 0 表示父栏目
        $date_time = date("Y-m-d H:i:s");
        $business_id = $request->business_info->id;

        $data["navigation_id"] = $navigation_id;
        $data["business_id"] = $business_id;
        $data["title"] = $title;
        $data["menu_type"] = $menu_type;
        $menu_url = Helper::getMenuUrl($menu_type, $subject_id);
        $data["menu_url"] = $menu_url;
        $data["subject_id"] = $subject_id;
        $data["position"] = $position;
        $data["parent_menu_id"] = $parent_menu_id;
        $data["created_at"] = $date_time;
        $data["updated_at"] = $date_time;

        try {
            $menu_id = Menus::insertGetId($data);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "The server encountered an error. Please try again later"]);
        }
        return response()->json(["status" => true, "data" => ["menu_id" => $menu_id]]);
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @param $menu_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $menu_id)
    {
        $business_id = $request->business_info->id;
        $page_info = Menus::where("business_id", $business_id)->where("id", $menu_id)->select("id as menu_id", "title", "menu_type", "subject_id", "position", "parent_menu_id")->first();
        if (empty($page_info)) {
            return response()->json(["status" => false, "errors" => "page_id is does not exist"]);
        }
        return response()->json(["status" => true, 'data' => $page_info]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $menu_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $menu_id)
    {

        $title = $request->input("title");
        if (empty($title)) {
            return response()->json(["status" => false, "errors" => "Title can't be blank"]);
        }
        $navigation_id = $request->input("navigation_id");
        if (empty($navigation_id)) {
            return Response()->json(["status" => false, "errors" => "navigation_id empty "]);
        }
        $menu_type = $request->input("menu_type");
        $subject_id = $request->input("subject_id");
        if (empty($menu_type)) {
            return Response()->json(["status" => false, "errors" => " Parameter is empty"]);
        }

        $position = $request->input("position", 1);
        $parent_menu_id = $request->input("parent_menu_id", 0); // 0 表示父栏目
        $date_time = date("Y-m-d H:i:s");
        $business_id = $request->business_info->id;

        $data["navigation_id"] = $navigation_id;
        $data["business_id"] = $business_id;
        $data["title"] = $title;
        $data["menu_type"] = $menu_type;
        $menu_url = Helper::getMenuUrl($menu_type, $subject_id);
        $data["menu_url"] = $menu_url;
        $data["subject_id"] = $subject_id;
        $data["position"] = $position;
        $data["parent_menu_id"] = $parent_menu_id;
        $data["updated_at"] = $date_time;

        try {
            Menus::where("business_id",$business_id)->where("id",$menu_id)->update($data);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "The server encountered an error. Please try again later"]);
        }
        return response()->json(["status" => true, 'msg' => "Update the collection is successful"]);
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
        $menu_id_list = $request->input("menu_id_list");
        if (!is_array($menu_id_list)) {
            return response()->json(["status" => false, "errors" => "menu_id_list Parameter error"]);
        }
        $business_id = $request->business_info->id;
        $msg = "action success";
        try {
            if ($type == 3) {
                Menus::where("business_id", $business_id)->whereIn("id", $menu_id_list)->delete();
                $msg = "successfully deleted";
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Operation failed Please try again later"]);
        }
        return response()->json(["status" => true, "msg" => $msg]);
    }


}
