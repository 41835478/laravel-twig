<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Pages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helper\Helper;


class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_pagesize = $request->input("per_pagesize", 20);
        $page = $request->input("page", 3);
        $business_id = $request->business_info->id;
        $params["business_id"] = $business_id;
        $offset = ($page - 1);
        if ($offset <= 0) {
            $offset = 0;
        }
        $data = Pages::where(function ($query) use ($params) {
            $query->where("business_id", $params["business_id"]);
        })->orderby("id", "asc")->select("id as page_id", "title", "content","up_and_down","updated_at")->offset($offset)->paginate($per_pagesize);
        if ($data->isEmpty()) {
            $result = [];
        } else {
            $result = Helper::pageFormat($data);
        }
        return response()->json(["status" => true, "data" => $result]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->input("title");
        if (empty($title)) {
            return response()->json(["status" => false, "errors" => "Title can't be blank"]);
        }
        $data["title"] = $title;
        $description = $request->input("description", "");
        $data["description"] = $description;
        $data["content"] = $request->input("content");
        $business_id = $request->business_info->id;
        $data["business_id"] = $business_id;

        $date_time = date("Y-m-d H:i:s");
        $data["content"] = $request->input("content");
        $data["template"] = $request->input("template");
        $data["up_and_down"] = $request->input("up_and_down",1);
        $data["created_at"] = $date_time;
        $data["updated_at"] = $date_time;

        try {
             $page_id =Pages::insertGetId($data);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "The server encountered an error. Please try again later"]);
        }
        return response()->json(["status" => true,"data"=>["page_id"=>$page_id]]);
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @param $page_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $page_id)
    {
        $business_id = $request->business_info->id;
        $page_info = Pages::where("business_id", $business_id)->where("id", $page_id)->select("id", "title", "content", "up_and_down", "page_title", "meta_description", "handle")->first();
        if (empty($page_info)) {
            return response()->json(["status" => false, "errors" => "page_id is does not exist"]);
        }
        return response()->json(["status" => true, 'data' => $page_info]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $page_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $page_id)
    {
        $title = $request->input("title");
        if (empty($title)) {
            return response()->json(["status" => false, "errors" => "Title can't be blank"]);
        }
        $data["title"] = $title;
        $description = $request->input("description", "");
        $data["description"] = $description;
        $business_id = $request->business_info->id;
        $data["business_id"] = $business_id;
        $date_time = date("Y-m-d H:i:s");
        $data["content"] = $request->input("content");
        $data["template"] = $request->input("template");
        $data["up_and_down"] = $request->input("up_and_down",1);
        $data["updated_at"] = $date_time;
        try {
            Pages::where("business_id",$business_id)->where("id",$page_id)->update($data);
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
        $page_id_list = $request->input("page_id_list");
        if (!is_array($page_id_list)) {
            return response()->json(["status" => false, "errors" => "collection_id_list Parameter error"]);
        }
        $business_id = $request->business_info->id;
        $msg = "action success";
        try {
            if ($type == 1) {
                Pages::where("business_id", $business_id)->whereIn("id", $page_id_list)->update(["up_and_down" => 1]);
                $msg = "Shelves success";
            } elseif ($type == 2) {
                Pages::where("business_id", $business_id)->whereIn("id", $page_id_list)->update(["up_and_down" => 2]);
                $msg = "Under the shelf success";
            } elseif ($type == 3) {
                Pages::where("business_id", $business_id)->whereIn("id", $page_id_list)->delete();
                $msg = "successfully deleted";
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Operation failed Please try again later"]);
        }
        return response()->json(["status" => true, "msg" => $msg]);
    }
}
