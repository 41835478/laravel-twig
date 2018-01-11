<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Collections;
use App\Http\Models\CollectionProducts;
use App\Http\Models\Products;
use App\Http\Helper\Helper;


class CollectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }

    public function show(Request $request, $collection_id)
    {
        $collection = Collections::getContent($collection_id);
        $page = $request->input("page",1);
        $page_size = 10;
        $total_size = CollectionProducts::getCount($collection_id);
        $total_page = ceil($total_size/$page_size);
        if($page <1){
            $page = 1;
        }
        if($page > $total_page){
            $page = $total_page ;
        }
        $offset = ($page -1 ) * $page_size ;

        $product_id_list = CollectionProducts::getPageProductsList($collection_id,$offset,$page_size);

        $product_list=[];
        if (!empty($product_id_list)){
            $product_id_list = $product_id_list->toArray();
            $product_list = Products::whereIn("id", $product_id_list)->where("up_and_down",1)->select("id", "platform_name", "spu_code", "spu_imgs", "product_name","price")->get();
            foreach ($product_list as $key=>$val) {
                $spu_imgs = !empty($val->spu_imgs) ? explode(";", $val->spu_imgs) : "";
                if (is_array($spu_imgs)) {
                    $spu_img = $spu_imgs[0];
                } else {
                    $spu_img = "";
                }
                unset($product_list[$key]["spu_imgs"]);
                $product_list[$key]["spu_img"] = $spu_img;
            }
        }
        $collection["all_products"] = $product_list;
        $collection = $collection->toArray();
        $collection["collection_id"] = $collection_id ;
        $url = Helper::getMenuUrl("collection",$collection_id);
        $url.= "?page=" ;
        return view("collections", ["collection" => $collection,"page_total"=>$total_page,"current_page"=>$page,"path"=>$url]);
    }
}
