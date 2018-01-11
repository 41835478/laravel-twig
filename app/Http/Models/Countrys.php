<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Countrys extends Model
{
    public $table = "country";

   public static function getList(){
        $list = DB::table("areas")->where("deep",2)->get()->pluck("areaEnName","areaId");
        return $list->toArray();
    }

}
