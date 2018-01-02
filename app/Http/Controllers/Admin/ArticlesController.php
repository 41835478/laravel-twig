<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Articles;
use App\Http\Models\BusinessAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Http\Helper\Helper;


class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_pagesize = $request->input("per_pagesize", 20);
        $page = $request->input("page", 1);
        $business_id = $request->business_info->id;
        $params["business_id"] = $business_id;
        $offset = ($page - 1);
        if ($offset <= 0) {
            $offset = 0;
        }
        $params["title"] = $request->input("title", "");
        $data = Articles::where(function ($query) use ($params) {
            if ($params['title']) {
                $query->where('articles.title', "like", "%{$params["title"]}%");
            }
            $query->where("articles.business_id", $params["business_id"]);
        })->leftjoin("blogs","articles.blog_id","blogs.id")->orderby("id", "desc")->offset($offset)->limit($per_pagesize)->select("articles.id", "articles.title", "blogs.id as blog_id","blogs.title as blog_title","articles.up_and_down","articles.featured_image", "articles.author", "articles.updated_at")->paginate($per_pagesize);
        if ($data->isEmpty()) {
            $result = [];
        } else {
            $result = Helper::pageFormat($data);
        }
        return response()->json(["status" => true, 'data' => $result]);
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
            'content.required' => 'content not empty',
            "blog_id.required" => "blog is empty",
            "up_and_down.required" => "visibility is empty",
        ];
        $validator = Validator::make($data, [
            'title' => 'required',
            'content' => 'required',
            'blog_id' => 'required',
            'up_and_down' => 'required',
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
        $result["content"] = $data["content"];
        $result["comments"] = isset($result["comments"])?$result["comments"]:1;
        $result["description"] = isset($result["description"])?$result["description"]:"";

        $result["excerpt"] = isset($data["excerpt"]) ? $data["excerpt"] : "";
        $result["page_title"] = isset($data["page_title"]) ? $data["page_title"] : $result["title"];
        $result["meta_description"] = isset($data["meta_description"]) ? $data["meta_description"] : $result["title"];

        $handle = isset($data["handle"]) ? $data["handle"] : $data["title"];
        //$handle = Articles::checkHandle($business_id, $check_handle);
        $result["handle"] = $handle;
        $business_address = BusinessAddress::getBusinessAddress($business_id);
        $result["author"] = $business_address->first_name . $business_address->last_name;
        $result["up_and_down"] = isset($data["up_and_down"]) ? $data["up_and_down"] : 1;

        $result["blog_id"] = $data["blog_id"];
        $result["featured_image"] = isset($data["featured_image"]) ? $data["featured_image"] : "";
        $result["created_at"] = date("Y-m-d H:i:s");
        $result["updated_at"] = date("Y-m-d H:i:s");
        try {
            $article_id = Articles::insertGetId($result);
        } catch (\Exception $e) {
            return response()->json(["status" => false, 'errors' => "Server error try again later" . $e->getMessage()]);
        }
        return response()->json(["status" => true, "data" => ["article_id" => $article_id]]);
    }

    /**
     * @param Request $request
     * @param $article_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $article_id)
    {
        $business_id = $request->business_info->id;
        $blog_info = Articles::where("business_id", $business_id)->where("id", $article_id)->select("id", "title", "blog_id", "content","comments","description", "up_and_down","featured_image", "author", "updated_at")->first();
        unset($blog_info->business_id);
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
            'content.required' => 'content not empty',
            "blog_id.required" => "blog is empty",
            "up_and_down.required" => "up_to_down is empty",
        ];
        $validator = Validator::make($data, [
            'title' => 'required',
            'content' => 'required',
            'blog_id' => 'required',
            'up_and_down' => 'required',
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
        $model = Articles::where(["business_id" => $business_id, "id" => $blog_id])->first();
        if (empty($model)) {
            return response()->json(["status" => false, 'errors' => "blog not exist"]);
        }
        $business_id = $request->business_info->id;
        $result = [];
        $result["business_id"] = $business_id;
        $result["title"] = $data["title"];
        $result["content"] = $data["content"];
        $result["comments"] = isset($result["comments"])?$result["comments"]:1;
        $result["description"] = isset($result["description"])?$result["description"]:"";

        $result["excerpt"] = isset($data["excerpt"]) ? $data["excerpt"] : "";
        $result["page_title"] = isset($data["page_title"]) ? $data["page_title"] : $result["title"];
        $result["meta_description"] = isset($data["meta_description"]) ? $data["meta_description"] : $result["title"];

        $handle = isset($data["handle"]) ? $data["handle"] : $data["title"];
        //$handle = Articles::checkHandle($business_id, $check_handle);
        $result["handle"] = $handle;
        $business_address = BusinessAddress::getBusinessAddress($business_id);
        $result["author"] = $business_address->first_name . $business_address->last_name;
        $result["up_and_down"] = isset($data["up_and_down"]) ? $data["up_and_down"] : 1;

        $result["blog_id"] = $data["blog_id"];
        $result["featured_image"] = isset($data["featured_image"]) ? $data["featured_image"] : "";
        $result["updated_at"] = date("Y-m-d H:i:s");

        try {
            Articles::where(["business_id" => $business_id, "id" => $blog_id])->update($result);
        } catch (\Exception $e) {
            return response()->json(["status" => false, 'errors' => "Server error try again later" . $e->getTrace()]);
        }
        return response()->json(["status" => true, "msg" =>"Update the article is successful"]);
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
        $article_id_list = $request->input("article_id_list");
        if (!is_array($article_id_list)) {
            return response()->json(["status" => false, "errors" => "article_id_list Parameter error"]);
        }
        $business_id = $request->business_info->id;
        $msg = "action success";
        try {
            if ($type == 1) {
                Articles::where("business_id", $business_id)->whereIn("id", $article_id_list)->update(["up_and_down" => 1]);
                $msg = "Shelves success";
            } elseif ($type == 2) {
                Articles::where("business_id", $business_id)->whereIn("id", $article_id_list)->update(["up_and_down" => 2]);
                $msg = "Under the shelf success";
            } elseif ($type == 3) {
                Articles::where("business_id", $business_id)->whereIn("id", $article_id_list)->delete();
                $msg = "successfully deleted";
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Operation failed Please try again later"]);
        }
        return response()->json(["status" => true, "msg" => $msg]);
    }
}
