<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionProducts extends Model
{

    public static function getCount($collection_id){
        return self::where("collection_id",$collection_id)->count();
    }

    public static function getProductsList($collection_id){
        $product_id_list = self::where("collection_id",$collection_id)->get()->pluck("product_id","id");
        return $product_id_list;
    }

    public static function getPageProductsList($collection_id,$offset,$page_size){
        $product_id_list = self::where("collection_id",$collection_id)->where("up_and_down",1)->offset($offset)->limit($page_size)->get()->pluck("product_id","id");
        return $product_id_list;
    }


}
