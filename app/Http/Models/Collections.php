<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Collections extends Model
{
    public static function getContent($collection){
        if (is_array($collection)){
            $content = self::whereIn("id",$collection)->select("id","title","description","cover_images","page_title","meta_description","handle")->get();
        }else{
            $content = self::where("id",$collection)->select("title","description","cover_images","page_title","meta_description","handle")->first();
        }
        return $content;
    }
}
