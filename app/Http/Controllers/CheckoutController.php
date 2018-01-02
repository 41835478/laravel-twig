<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Models\AccountsAddress;

use App\Http\Models\Carts;
use App\Http\Models\Areas;
use App\Services\SuperBuyOpenApi;
use App\Http\Models\Products;


class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }

    public function index(Request $request)
    {


        //不同的
        $step = $request->input("step", "contact_information");
        if ($step == "contact_information") {
            $users = session()->get("users");
            $account_id = $users["member_id"];
            $business_info = session()->get("business_info");
            $business_id = $business_info["id"];

            $carts = $this->getCarts();
            list($result, $total_price) = Carts::FormatProduct($carts, $business_id);
            $country_list = Areas::getCountryList();
            $address_list = AccountsAddress::getAddress($account_id);

            return view("shiping_customer", ["country_list" => $country_list, "address_list" => $address_list, "cart_list" => $result, "total_price" => $total_price]);

        } elseif ($step == "shipping_method") {
            //先更新收获地址信息
            $users = session()->get("users");
            //更新用户的物流信息
            $account_id = $users["member_id"];
            $address = $request->input("address");
            $address_id = $address["id"];
            $data = AccountsAddress::formatData($account_id, $address);

            AccountsAddress::where("account_id", $account_id)->where("id", $address_id)->update($data);

            $business_info = session()->get("business_info");
            $business_id = $business_info["id"];

            $carts = $this->getCarts();

            list($result, $total_price) = Carts::FormatProduct($carts, $business_id);

            $address = $request->input("address");
            $address_id = $address["id"];
            $shipping_address = AccountsAddress::getAddressInfo($address_id);


            $product_id_list = array_keys($carts);
            foreach ($product_id_list as $key => $value) {
                $item = explode("_", $value);
                $product_id_list[$key] = $item[0];
            }

            $total_weight = Products::getWeight($product_id_list);

            $rate_data = ["weight" => $total_weight, "length" => 0, "height" => 0, "width" => 0, "destination" => $data["country_id"], "warehouse" => 1];
            $rate_list = SuperBuyOpenApi::getRateQuote($rate_data);
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
            return view("shiping_method", ["cart_list" => $result, "total_price" => $total_price, "shipping_address" => $shipping_address, "rate" => $rate]);

        } elseif ($step == "payment_method") {
            $business_info = session()->get("business_info");
            $business_id = $business_info["id"];

            $carts = $this->getCarts();
            list($result, $total_price) = Carts::FormatProduct($carts, $business_id);

            return view("payment_method",["cart_list" => $result, "total_price" => $total_price]);

        }

    }


}
