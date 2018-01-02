<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
        public static function getWeight($product_id_list){
            return self::whereIn("id",$product_id_list)->sum("weight");
        }
}
