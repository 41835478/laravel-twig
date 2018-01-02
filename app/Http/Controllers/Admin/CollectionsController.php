<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Collections;
use App\Http\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helper\Upload;
use App\Http\Models\CollectionProducts;
use App\Http\Helper\Helper;


class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_pagesize = $request->input("per_pagesize", 1);
        $page = $request->input("page", 3);
        $business_id = $request->business_info->id;
        $params["business_id"] = $business_id;
        $offset = ($page - 1);
        if ($offset <= 0) {
            $offset = 0;
        }
        $data = Collections::where(function ($query) use ($params) {
            $query->where("business_id", $params["business_id"]);
        })->orderby("id", "asc")->select("id as collection_id", "title", "cover_images")->offset($offset)->paginate($per_pagesize);
        if ($data->isEmpty()) {
            $result = [];
        } else {
            $result = Helper::pageFormat($data);
            foreach ($result["data"] as $key => $value) {
                $result["data"][$key]["products_count"] = CollectionProducts::getCount($value["collection_id"]);
            }
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
        if (!empty($request->file("file")) ) {
            $file_url = Upload::upload_file($request);
            if ($file_url) {
                $data["cover_images"] = $file_url;
            }
        }else{
            $data["cover_images"] = $request->input("file");
        }

        $product_id_list = $request->input("product_id_list", "");
        $business_id = $request->business_info->id;
        $data["business_id"] = $business_id;
        $date_time = date("Y-m-d H:i:s");
        $data["created_at"] = $date_time;
        $data["updated_at"] = $date_time;
        try {
            $collection_id = Collections::insertGetId($data);
            if (!empty($product_id_list)) {
                foreach ($product_id_list as $key => $value) {
                    CollectionProducts::updateOrCreate(["collection_id" => $collection_id, "product_id" => $value], ["collection_id" => $collection_id, "product_id" => $value, "created_at" => $date_time, "updated_at" => $date_time]);
                }
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "The server encountered an error. Please try again later"]);
        }
        return response()->json(["status" => true, "data" => ["collection_id" => $collection_id]]);
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @param $collection_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $collection_id)
    {
        $business_id = $request->business_info->id;
        $collection_info = Collections::where("business_id", $business_id)->where("id", $collection_id)->select("id", "title", "description", "cover_images", "page_title", "meta_description", "handle")->first();
        if (empty($collection_info)) {
            return response()->json(["status" => false, "errors" => "collection_id is does not exist"]);
        }
        $product_id_list = CollectionProducts::getProductsList($collection_info->id);
        if ($product_id_list->isEmpty()) {
            $collection_info["product_list"] = [];
        } else {
            $product_list_info = Products::where("business_id", $business_id)->whereIn("id", $product_id_list)->select("id as product_id", "product_name", "spu_imgs")->get();
            foreach ($product_list_info as $key => $value) {
                $spu_imgs = !empty($value->spu_imgs) ? explode(";", $value->spu_imgs) : "";
                if (is_array($spu_imgs)) {
                    $product_list_info[$key]["spu_img"] = $spu_imgs[0];
                } else {
                    $product_list_info[$key]["spu_img"] = "";
                }
                unset($product_list_info[$key]["spu_imgs"]);
            }
            $collection_info["product_list"] = $product_list_info;
        }
        return response()->json(["status" => true, 'data' => $collection_info]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $collection_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $collection_id)
    {

        $title = $request->input("title");
        if (!empty($title)) {
            $data["title"] = $title;
        }
        $description = $request->input("description", "");
        $data["description"] = $description;
        if (!empty($request->file("file")) ) {
            $file_url = Upload::upload_file($request);
            if ($file_url) {
                $data["cover_images"] = $file_url;
            }
        }else{
            $data["cover_images"] = $request->input("file");
        }

        $product_id_list = $request->input("product_id_list", "");
        $business_id = $request->business_info->id;
        $data["business_id"] = $business_id;
        $date_time = date("Y-m-d H:i:s");
        $data["updated_at"] = $date_time;
        $action = $request->input("action", 1); // 1 新增商品到新的集合 2 更新（增加的新增 此集合中没有的从原集合中删除） 3 删除的其中的商品
        // 获取已选的集合
        $product_has_id_list = CollectionProducts::getProductsList($collection_id);

        try {
            if (!empty($action) && intval($action) == 1) {
                if ($product_has_id_list->isEmpty()) {
                    $has_add_product_list = $product_id_list;
                } else {
                    $has_add_product_list = array_diff($product_id_list, $product_has_id_list->toArray());
                }

                if (is_array($has_add_product_list) && !empty($has_add_product_list)) {
                    foreach ($has_add_product_list as $key => $value) {
                        CollectionProducts::insert(["collection_id" => $collection_id, "product_id" => $value, "created_at" => $date_time, "updated_at" => $date_time]);
                    }
                }
            } else if (!empty($action) && intval($action) == 2) {
                if ($product_has_id_list->isEmpty()) {
                    $has_add_product_list = $product_id_list;
                    $has_delete_product_list = false;
                } else {
                    $has_add_product_list = array_diff($product_id_list, $product_has_id_list->toArray());
                    $has_delete_product_list = array_diff($product_has_id_list->toArray(), $product_id_list);
                }
                if (is_array($has_add_product_list) && !empty($has_add_product_list)) {
                    foreach ($has_add_product_list as $key => $value) {
                        CollectionProducts::insert(["collection_id" => $collection_id, "product_id" => $value, "created_at" => $date_time, "updated_at" => $date_time]);
                    }
                }
                if (is_array($has_delete_product_list) && !empty($has_delete_product_list)) {
                    CollectionProducts::where("collection_id", $collection_id)->whereIn("product_id", $has_delete_product_list)->delete();
                }

            } else if (!empty($action) && intval($action) == 3) {
                CollectionProducts::where("collection_id", $collection_id)->whereIn("product_id", $product_id_list)->delete();
            }

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
    public function actions(Request $request)
    {
        $type = $request->input("type", "");
        if (empty($type)) {
            return response()->json(["status" => false, "errors" => "The type of operation is empty"]);
        }
        $collection_id_list = $request->input("collection_id_list");
        if (!is_array($collection_id_list)) {
            return response()->json(["status" => false, "errors" => "collection_id_list Parameter error"]);
        }
        $business_id = $request->business_info->id;
        $msg = "action success";
        try {
            if ($type == 1) {
                Collections::where("business_id", $business_id)->whereIn("id", $collection_id_list)->update(["up_and_down" => 1]);
                $msg = "Shelves success";
            } elseif ($type == 2) {
                Collections::where("business_id", $business_id)->whereIn("id", $collection_id_list)->update(["up_and_down" => 2]);
                $msg = "Under the shelf success";
            } elseif ($type == 3) {
                Collections::where("business_id", $business_id)->whereIn("id", $collection_id_list)->delete();
                CollectionProducts::whereIn("collection_id", $collection_id_list)->delete();// 后面要加上business_id 和校验
                $msg = "successfully deleted";
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Operation failed Please try again later"]);
        }
        return response()->json(["status" => true, "msg" => $msg]);
    }



}
