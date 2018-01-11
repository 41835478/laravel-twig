<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Orders;


class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }


    function index(Request $request){
        $users = $request->session()->get("users");
        if (empty($users)){
            return redirect('/');
        }
        $account_id = $users["member_id"];
        $business_id = $this->getBusinessId();
        $order_list = Orders::where("business_id",$business_id)->where("member_id",$account_id)->get();

        return view("orders",[]);
    }


}
