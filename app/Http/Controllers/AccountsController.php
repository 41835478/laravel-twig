<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Models\BusinessAccounts;
use Illuminate\Support\Facades\Session;
use App\Http\Models\AccountsAddress;
use App\Http\Models\Carts;
use App\Http\Models\Orders;


class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function create(Request $request)
    {
        return view("register");
    }

    function register(Request $request)
    {
        $customer = $request->input("customer");
        if (empty($customer["first_name"])) {
            return redirect('account/register')->with('errors', 'first_name is empty');
        }
        if (empty($customer["last_name"])) {
            return redirect('account/register')->with('errors', 'last_name is empty');
        }
        if (empty($customer["email"])) {
            return redirect('account/register')->with('errors', 'email is empty');
        }
        if (empty($customer["password"])) {
            return redirect('account/register')->with('errors', 'password is empty');
        }
        // 检查如果注册过提示用户
        /*
        * <ul>
        <li>This email address is already associated with an account. If this account is yours, you can
        <a href="/account/login#recover">reset your password</a>
        </li>
        </ul>
       */
        $email = $customer["email"];
        $business_info = $request->session()->get("business_info");
        $business_id = $business_info["id"];
        $check_email = BusinessAccounts::where("business_id", $business_id)->where("email", $email)->value("id");
        if (!empty($check_email)) {
            return redirect('account/register')->with('errors', 'This email address is already associated with an account');
        }
        $data["business_id"] = $business_id;
        $data["first_name"] = $customer["first_name"];
        $data["last_name"] = $customer["last_name"];
        $data["email"] = $customer["email"];
        $data["password"] = md5($customer["password"] . config("app.m_key"));
        $date_time = date("Y-m-d H:i:s");
        $data["created_at"] = $date_time;
        $data["updated_at"] = $date_time;

        try {
            $member_id = BusinessAccounts::insertGetId($data);
            $data["member_id"] = $member_id;
            unset($data["password"]);
            Session::put("users", $data); // 用户的 session 信息存入
        } catch (\Exception $e) {
            return redirect('account/register')->with('errors', 'Registration failed Server error occurred');
        }
        // 注册成功 完成跳转到用户中心页
        return redirect('account');
    }


    public function login(Request $request)
    {
        $request->getContent();
        $method = $request->getMethod();
        if ($method == "GET") {
            return view("login");
        } elseif ($method == "POST") {
            $customer = $request->input("customer");
            if (empty($customer["email"])) {
                return redirect('account/login')->with('errors', 'email is empty');
            }
            if (empty($customer["password"])) {
                return redirect('account/login')->with('errors', 'password is empty');
            }
            $business_info = $request->session()->get("business_info");
            $business_id = $business_info["id"];
            $email = $customer["email"];
            $password = $customer["password"];
            $user_info = BusinessAccounts::where("business_id", $business_id)->where("email", $email)->select("id as member_id", "first_name", "last_name", "password","email")->first();
            if (empty($user_info)) {
                return redirect('account/login')->with('errors', 'Invalid login credentials');
            }
            $password = md5($password . config("app.m_key"));
            if ($user_info->password != $password) {
                return redirect('account/login')->with('errors', 'Invalid login credentials.');
            }
            unset($user_info["password"]);
            $user_info["business_id"] = $business_id;
            Session::put("users", $user_info->toArray()); // 用户的 session 信息存入
            $carts =session()->get('carts');
            if(!empty($carts)){
                $has_carts = Carts::getCarts($user_info["member_id"],$business_id);
                if(!empty($has_carts)){
                    $merge_carts = array_merge($has_carts,$carts);
                    Carts::addToCart($user_info["member_id"],$business_id,$merge_carts);
                }else{
                    Carts::addToCart($user_info["member_id"],$business_id,$carts);
                }
            }
            return redirect('account');
        }
    }

    public function show(Request $request)
    {
        $users = $request->session()->get("users");
        if (empty($users)){
            return redirect('/');
        }
        $account_id = $users["member_id"];
        $address = AccountsAddress::where("account_id",$account_id)->where("default",1)->first();
        $count = AccountsAddress::where("account_id",$account_id)->count();
        $address["count"] = $count;
        $business_id = $this->getBusinessId();
        //用户的订单数据 创建未支付的 24小时重新标记订单过期 把过期数据重新放入购物车中 （）
        $count = Orders::where("business_id",$business_id)->where("member_id",$account_id)->count();
        $orders["count"] = $count;
        return view("account",["address"=>$address,"orders"=>$orders]);
    }

    public function logout(Request $request)
    {
        $carts = session()->get("users.carts"); // 用户的 session 信息存入
        if(!empty($carts)){
            session()->put('carts',$carts);
        }
        if(session()->has("users")){
            session()->forget("users");
        }
        $return_url = $request->input("return_url");
        if(!empty($return_url)){
            return redirect($return_url);
        }
        return redirect('/');
    }


}
