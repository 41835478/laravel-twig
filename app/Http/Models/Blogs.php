<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{

    public static function checkHandle($business_id, $handle)
    {
        $check_handle = Blogs::where("business_id", $business_id)->where("handle", $handle)->value("id");
        if (!empty($check_handle)) {
            //之前的已经有了 检查最大的数字是多少
            $last_number_check =  substr($handle,-1);
            if (is_numeric($last_number_check)){
                $return_handle = substr($handle, 0, strlen($handle) - 1).($last_number_check + 1);
                return $return_handle;
            }
            $max_handle = Blogs::where("business_id", $business_id)->where("handle", "like", "%{$handle}-%")->orderby("id", "desc")->value("handle");
            if (!empty($max_handle)) {
                $last_number = substr($max_handle, -1);
                $return_handle = is_numeric($last_number) ? substr($max_handle, 0, strlen($max_handle) - 1) . ($last_number + 1) : $handle . "-1";
            }else{
                $return_handle = $handle . "-1";
            }
        } else {
            $return_handle = $handle;
        }
        return $return_handle;
    }
}
