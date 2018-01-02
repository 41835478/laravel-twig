<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Models\MongoProducts;
use App\Http\Helper\Helper;
use App\Http\Models\Carts ;

class CartsController extends Controller
{
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }

    function index(Request $request)
    {
        $users = $request->session()->get("users");
        $business_info = $request->session()->get("business_info");
        $business_id = $business_info["id"];
        $member_id = $users["member_id"];
        $carts = $this->getCarts();

        list($result,$total_price) = Carts::FormatProduct($carts,$business_id);
        return view("cart", ["cart_list" => $result, "total_price" => $total_price]);
    }


    function store(Request $request)
    {
        //记录用户的购物车信息 product_id spu_code quantity
        $users = $request->session()->get("users");
        $check_login = false;
        if (!empty($users)) {
            $check_login = true;
        }
        $carts = $this->getCarts();

        $product_id = $request->input("product_id");
        $spuCode = $request->input("spuCode");
        $quantity = $request->input("quantity");

        $business_info = $request->session()->get("business_info");
        $business_id = $business_info["id"];
        $cart_unique_code = $product_id . "_" . $spuCode;
        $cart_data = ["product_id" => $product_id, "spuCode" => $spuCode, "quantity" => $quantity, "business_id" => $business_id];
        $data[$cart_unique_code] = $cart_data;

        // 只记录本此加入的 多次的 要合并操作
        if (empty($carts)) {
            if ($check_login) {
                $member_id =$users["member_id"];
                Carts::addToCart($member_id,$business_id,$data);
            } else {
                Session::put("carts", $data);
            }
        } else {
            //已经登陆 从数据库读购物车数据
            $already_cart_unique = array_keys($carts);
            if (!in_array($cart_unique_code, $already_cart_unique)) {
                $carts[$cart_unique_code] = $cart_data;
            } else {
                // $carts
                $old_cart_data = $carts[$cart_unique_code];
                $quantity_old = $old_cart_data["quantity"];
                $cart_data["quantity"] = $cart_data["quantity"] + $quantity_old;
                $carts[$cart_unique_code] = $cart_data;
            }
            if ($check_login) {

                $member_id =$users["member_id"];
                //更新数据库
                Carts::addToCart($member_id,$business_id,$carts);
            } else {
                //更新临时session
                Session::put("carts", $carts);
            }
        }

        return redirect('carts');
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    function change(Request $request)
    {
        $id = $request->input("id");
        $action = $request->input("action");
        if ($action == "remove") {
            $users = $request->session()->get("users");
            $carts = $this->getCarts();
            if (array_key_exists($id, $carts)) {
                unset($carts[$id]);
            }

            if (empty($users)) {
                Session::put("carts", $carts);
            } else {
                $result = explode("_",$id);
                $member_id =$users["member_id"];
                $business_info = $request->session()->get("business_info");
                $business_id = $business_info["id"];
                if(is_array($result)){
                    Carts::updateToCart($member_id,$business_id,$result[0],$result[1],"remove",null);
                }
            }
            return redirect('carts');
        } elseif ($action == "update_quantity") {
            $id = $request->input("id");
            $users = $request->session()->get("users");
            $carts = $this->getCarts();
            if(isset($carts[$id])){
                $quantity = $request->input("quantity");
                $carts[$id]["quantity"] = $quantity ;
                if (empty($users)) {
                    Session::put("carts", $carts);
                } else {
                    $result = explode("_",$id);
                    $member_id =$users["member_id"];
                    $business_info = $request->session()->get("business_info");
                    $business_id = $business_info["id"];
                    if(is_array($result)){
                        Carts::updateToCart($member_id,$business_id,$result[0],$result[1],"update_quantity",$quantity);
                    }
                }
            }
            return response()->json(["status" => true]);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    function checkout(Request $request)
    {
        //确认信息
        return redirect('/checkout?step=contact_information');
    }


}
