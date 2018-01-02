<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Blogs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input("per_pagesize", 20);
        $page = $request->input("page", 1);
        $business_id = $request->business_info->id;
        $params["business_id"] = $business_id;
        $offset = ($page - 1);
        if ($offset <= 0) {
            $offset = 0;
        }
        $params["title"] = $request->input("title", "");
        $data = Blogs::where(function ($query) use ($params) {
            if ($params['title']) {
                $query->where('title', "like", "%{$params["title"]}%");
            }
            $query->where("business_id", $params["business_id"]);
        })->orderby("id", "desc")->offset($offset)->limit($limit)->select("id as blog_id", "title", "comments")->get();

        return response()->json(["status" => true, 'data' => $data]);

    }

    /**
     * Show the form for creating a new resource.
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->getContent();
        $data = json_decode($data, true);
        if (empty($data)) {
            return response()->json(["status" => false, 'errors' => 'json 格式有错']);
        }
        $messages = [
            'title.required' => 'title not empty',
        ];
        $validator = Validator::make($data, [
            'title' => 'required',
        ], $messages);

        $msg = "";
        if (!$validator->passes()) {
            $tmp_msg = $validator->errors()->messages();
            foreach ($tmp_msg as $value) {
                $msg = $value[0];
                break;
            }
            return response()->json(["status" => false, 'errors' => $msg]);
        }
        $business_id = $request->business_info->id;
        $result = [];
        $result["business_id"] = $business_id;
        $result["title"] = $data["title"];
        $result["page_title"] = isset($data["page_title"]) ? $data["page_title"] : $result["title"];
        $result["meta_description"] = isset($data["meta_description"]) ? $data["meta_description"] : $result["title"];
        $check_handle = isset($data["handle"]) ? $data["handle"] : $data["title"];
        $handle = Blogs::checkHandle($business_id, $check_handle);
        $result["handle"] = $handle;
        $result["comments"] = isset($data["comment"]) ? $data["comment"] : 1;
        $result["created_at"] = date("Y-m-d H:i:s");
        $result["updated_at"] = date("Y-m-d H:i:s");
        try {
            $blog_id=Blogs::insertGetId($result);
        } catch (\Exception $e) {
            return response()->json(["status" => false, 'errors' => "Server error try again later" . $e->getMessage()]);
        }
        return response()->json(["status" => true, "data" => ["blog_id"=>$blog_id]]);
    }

    /**
     * @param Request $request
     * @param $blog_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $blog_id)
    {
        $business_id = $request->business_info->id;
        $blog_info = Blogs::where("business_id", $business_id)->where("id", $blog_id)->select("title", "page_title", "id", "meta_description", "handle", "comments")->first();
        return response()->json(["status" => true, 'data' => $blog_info]);
    }

    /**
     * @param Request $request
     * @param $blog_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $blog_id)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);
        if (empty($data)) {
            return response()->json(["status" => false, 'errors' => 'json 格式有错']);
        }
        $messages = [
            'title.required' => 'title not empty',
        ];
        $validator = Validator::make($data, [
            'title' => 'required',
        ], $messages);

        $msg = "";
        if (!$validator->passes()) {
            $tmp_msg = $validator->errors()->messages();
            foreach ($tmp_msg as $value) {
                $msg = $value[0];
                break;
            }
            return response()->json(["status" => false, 'errors' => $msg]);
        }
        $business_id = $request->business_info->id;
        $model = Blogs::where(["business_id" => $business_id, "id" => $blog_id])->first();
        if (empty($model)) {
            return response()->json(["status" => false, 'errors' => "blog not exist"]);
        }
        $result["business_id"] = $business_id;
        $result["title"] = $data["title"];
        $result["page_title"] = isset($data["page_title"]) ? $data["page_title"] : $result["title"];
        $result["meta_description"] = isset($data["meta_description"]) ? $data["meta_description"] : $result["title"];
        $check_handle = isset($data["handle"]) ? $data["handle"] : $data["title"];
        $handle = Blogs::checkHandle($business_id, $check_handle);
        $result["handle"] = $handle;
        $result["comments"] = isset($data["comment"]) ? $data["comment"] : 1;
        $result["updated_at"] = date("Y-m-d H:i:s");
        try {
            Blogs::where(["business_id" => $business_id, "id" => $blog_id])->update($result);
        } catch (\Exception $e) {
            return response()->json(["status" => false, 'errors' => "Server error try again later" . $e->getTrace()]);
        }
        return response()->json(["status" => true, "msg" => "Update the collection is successful"]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function action(Request $request)
    {
        $type = $request->input("type", "");
        if (empty($type)) {
            return response()->json(["status" => false, "errors" => "The type of operation is empty"]);
        }
        $blog_id_list = $request->input("blog_id_list");
        if (!is_array($blog_id_list)) {
            return response()->json(["status" => false, "errors" => "collection_id_list Parameter error"]);
        }
        $business_id = $request->business_info->id;
        $msg = "action success";
        try {
            if ($type == 1) {
                Blogs::where("business_id", $business_id)->whereIn("id", $blog_id_list)->update(["up_and_down" => 1]);
                $msg = "Shelves success";
            } elseif ($type == 2) {
                Blogs::where("business_id", $business_id)->whereIn("id", $blog_id_list)->update(["up_and_down" => 2]);
                $msg = "Under the shelf success";
            } elseif ($type == 3) {
                Blogs::where("business_id", $business_id)->whereIn("id", $blog_id_list)->delete();
                $msg = "successfully deleted";
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Operation failed Please try again later"]);
        }
        return response()->json(["status" => true, "msg" => $msg]);
    }
}
