<?php

namespace App\Http\Controllers;

use App\Http\Models\CollectionProducts;
use App\Http\Models\Products;
use Illuminate\Http\Request;

use Twig;
use App\Http\Models\Collections;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }

    /**
     * Show the application dashboard.
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 解析 content_for_index
        /*$setting = $request["setting"];
        $data = [];
        $content_for_index = isset($setting["current"]["content_for_index"])?$setting["current"]["content_for_index"]:[];
        $sections = $setting["current"]["sections"];
        $collection_list = [];
        foreach ($content_for_index as $section_id) {
            $collection_id = $sections[$section_id]["settings"]["collection"];
            //目前硬编码 到产品 section
            $collection_list[] = $collection_id;
        }
        */
//        dd($request->session());die;
        $data= [];
        $business_info = $request->session()->get("business_info");
        $business_id = $business_info["id"];
        $collection_list = Collections::where("business_id",$business_id)->where("up_and_down",1)->pluck("id","title");
        if($collection_list->isNotEmpty()){
            $collection_list = $collection_list->toArray();
            $content = Collections::getContent($collection_list);
            foreach ($content as $value) {
                $data[$value->id] = $value->toArray();
                $product_id_list = CollectionProducts::getProductsList($value->id);
                $product_list = Products::whereIn("id", $product_id_list)->where("up_and_down",1)->select("id", "platform_name", "spu_code", "spu_imgs", "product_name","price")->limit(8)->get();
                foreach ($product_list as $val) {
                    $spu_imgs = !empty($val->spu_imgs) ? explode(";", $val->spu_imgs) : "";
                    if (is_array($spu_imgs)) {
                        $spu_img = $spu_imgs[0];
                    } else {
                        $spu_img = "";
                    }
                    $data[$value->id]["all_product"][$val->id]["spu_img"] = $spu_img;
                    $data[$value->id]["all_product"][$val->id]["product_name"] = $val->product_name;
                    $data[$value->id]["all_product"][$val->id]["price"] = $val->price;
                }
            }
        }

        return view("index", [ "collections" => $data]);

    }

    public function collections()
    {
        return view("collection", []);
    }


}
