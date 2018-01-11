<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Models\AccountsAddress;

use App\Http\Models\Carts;
use App\Http\Models\Areas;
use App\Services\SuperBuyOpenApi;
use App\Http\Models\Products;
use Illuminate\Support\Facades\Session;
use App\Http\Helper\Helper;



class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }

    public function index(Request $request, $sign)
    {

        $carts = Session::get("checkouts");
        $check_sign = md5(json_encode($carts) . config("app.key"));
        if ($check_sign != $sign) {
            return redirect('/carts')->with('success', 'You visit the checkout address wrong');
        }
        //不同的
        $step = $request->input("step", "contact_information");
        if ($step == "contact_information") {

            $users = session()->get("users");
            $account_id = $users["member_id"];
            $business_id = $this->getBusinessId();

            list($result, $total_price) = Carts::FormatProduct($carts, $business_id);
            $country_list = Areas::getCountryList();
            $address_list = AccountsAddress::getAddress($account_id);
            if (empty($address_list)) {
                return redirect('/account')->with('success', 'Please fill in your shipping address information');
            }
            $price = [
                "subtotal"=>$total_price,
                "total_price"=>$total_price
            ];
            return view("shiping_customer", ["sign" => $sign, "country_list" => $country_list, "address_list" => $address_list, "cart_list" => $result, "price" => $price]);

        } elseif ($step == "shipping_method") {
            //先更新收获地址信息
            $users = session()->get("users");
            //更新用户的物流信息
            $account_id = $users["member_id"];
            $address = $request->input("address", "");
            if (empty($address)) {
                return redirect("/checkouts/{$sign}?step=contact_information")->with('success', 'Please fill in your shipping address information');
            }

            $address_id = $address["id"];
            $data = AccountsAddress::formatData($account_id, $address);
            AccountsAddress::where("account_id", $account_id)->where("id", $address_id)->update($data);
            $business_id = $this->getBusinessId();
            list($result, $total_price) = Carts::FormatProduct($carts, $business_id);
            $shipping_address = AccountsAddress::getAddressInfo($address_id);

            $product_id_list = array_keys($carts);
            foreach ($product_id_list as $key => $value) {
                $item = explode("_", $value);
                $product_id_list[$key] = $item[0];
            }

            //运费估算相关逻辑 要从后台对 并算出运费
            $total_weight = Products::getWeight($product_id_list);
            $rate_data = ["weight" => $total_weight, "length" => 0, "height" => 0, "width" => 0, "destination" => $data["country_id"], "warehouse" => 4];
            $open_supbuy_client = new SuperBuyOpenApi();

            $rate_list = $open_supbuy_client->getRateQuote($rate_data);
            $rate_list = json_decode($rate_list, true);
            $rate = [];
            if (isset($rate_list["state"]) && intval($rate_list["state"]) == 0) {
                $rate = $rate_list["data"];
            }

            foreach ($rate as $key => $value) {
                if ($value["available"] == false) {
                    unset($rate[$key]);
                }
            }
            //收货地址 邮寄运费列表
            Session::put("shipping_address", $shipping_address);
            Session::put("shipping_method", $rate);
            $price = [
                "subtotal"=>$total_price,
                "total_price"=>$total_price
            ];

            return view("shiping_method", ["sign" => $sign, "cart_list" => $result, "price" => $price, "shipping_address" => $shipping_address, "rate" => $rate]);

        } elseif ($step == "payment_method") {

            $shipping_rate = $request->input("shipping_rate", "");
            if(empty($shipping_rate)){
                return redirect("/checkouts/{$sign}?step=shipping_method")->with('success', 'Invoice sort flow method');
            }
            $business_id = $this->getBusinessId();
            $exchange_rate = 0.1538 ;

            list($result, $total_price) = Carts::FormatProduct($carts, $business_id);

            //选择邮寄的方式
            Session::put("shipping_method_id", $shipping_rate["id"]);
            $rate = Session::get("shipping_method");

            $select_shipping_method = [];
            $totalChargeUSD = 0.0 ;
            foreach ($rate as $key=>$value){
                if($value["productId"] == $shipping_rate["id"]){
                    $totalChargeUSD =  round(($value["totalCharge"]/100)*$exchange_rate,2) ;
                    $select_shipping_method = ["productId"=>$value["productId"],"productName"=>$value["productName"],"productEnName"=>$value["productEnName"],"totalCharge"=>$value["totalCharge"]/100,"totalChargeUSD"=>$totalChargeUSD];
                    break;
                }
            }
            Session::put("select_shipping_method",$select_shipping_method);

            $total_price_usd = $goods_total_price_usd+$totalChargeUSD;
            $price = [
                "subtotal"=>$goods_total_price_usd,
               "shipping"=>$totalChargeUSD,
               "total_price"=>$total_price_usd
            ];
            $shipping_address = Session::get("shipping_address");
            return view("payment_method", ["sign" => $sign, "cart_list" => $result, "price"=>$price,"shipping_address" => $shipping_address,"select_shipping_method"=>$select_shipping_method]);
        }

    }


}
