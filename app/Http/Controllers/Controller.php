<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Models\Carts;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function getCarts(){
        $users = session()->get("users");
        if (empty($users)) {
            // 获取记录的其他的购物车中信息是否有
            $carts = session()->get("carts");
        } else {
            $business_info = session()->get("business_info");
            $business_id = $business_info["id"];
            $member_id = $users["member_id"];
            //用户登陆直接从数据中读取
            $users_carts = Carts::getCarts($member_id,$business_id);
            session()->put("users.carts",$users_carts);
            $carts = $users_carts;
        }
        return $carts;
    }

    public function getBusinessId(){
        $business_info = session()->get("business_info");
        $business_id = $business_info["id"];
        return $business_id;
    }


}
