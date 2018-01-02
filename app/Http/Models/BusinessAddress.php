<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessAddress extends Model
{
    public $table = "business_address";

    public static function getBusinessAddress($business_id){
        return self::where("business_id",$business_id)->first();
    }
}
