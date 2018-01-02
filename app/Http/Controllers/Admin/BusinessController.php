<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Business;
use App\Http\Models\BusinessAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Jobs\BusinessPushStore;

class BusinessController extends Controller
{


    public function create(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);
        if (empty($data)) {
            return response()->json(["status" => false, 'errors' => 'json 格式有错']);
        }
        $messages = [
            'email.required' => 'email not empty',
            "email.unique" => "This email is already used",
            'password.required' => 'password not empty',
            'domain.required' => 'domain not empty',
        ];

        $validator = Validator::make($data, [
            'email' => 'required|email|unique:business',
            'password' => 'required',
            "domain" => "required"
        ], $messages);
        $msg = "";
        if (!$validator->passes()) {
            $tmp_msg = $validator->errors()->messages();
            foreach ($tmp_msg as $value) {
                $msg = $value[0];
            }
            return response()->json(["status" => false, 'errors' => $msg]);
        }
        $email = $data["email"];
        $password = md5($data["password"] . config("app.key"));
        $domain = $data["domain"];
        $api_token = str_random(60);
        $check_email = Business::where("email", $email)->value("email");
        if (!$check_email) {
            return response()->json(["status" => false, 'errors' => "E-mail already exists"]);
        }
        $check_domain = Business::where("domain", $domain)->value("domain");
        if (!$check_domain) {
            return response()->json(["status" => false, 'errors' => "Domain name is already occupied"]);
        }
        try {
            $date = date("Y-m-d H:i:s");
            $business_id = Business::insertGetId(["email" => $email, "password" => $password, "domain" => $domain, "api_token" => $api_token, "created_at" => $date, "updated_at" => $date]);

        } catch (\Exception $e) {
            return response()->json(["status" => false, 'errors' => 'server error .' . $e->getMessage()]);
        }
        response()->json(["status" => true, "data" => ["domain" => "http://{$domain}.buckydrop.com","business_id"=>intval($business_id),"token"=>$api_token]]);
    }

    public function login(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);
        if (empty($data)) {
            return response()->json(["status" => false, 'errors' => 'json 格式有错']);
        }
        $messages = [
            'email.required' => 'email not empty',
            'password.required' => 'password not empty',
        ];

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ], $messages);

        $msg = "";
        if (!$validator->passes()) {
            $tmp_msg = $validator->errors()->messages();
            foreach ($tmp_msg as $value) {
                $msg = $value[0];
                break;
            }
            return response()->json(["status" => false, 'errors' => $msg]);
        }
        $email = $data["email"];
        $password = $data["password"];
        $business_info = BUsiness::where("email", $email)->first();
        if (empty($business_info)) {
            return response()->json(["status" => false, 'errors' => 'Username and password failed']);
        }
        $base_password = $business_info->password;
        if ($base_password != md5($password . config("app.key"))) {
            return response()->json(["status" => false, 'errors' => 'Username and password failed']);
        }
        // 检查是否设置店铺 基本信息
        $business_address = BusinessAddress::where("business_id", $business_info->id)->value("business_id");
        if (empty($business_address)) {
            $has_address = false;
        } else {
            $has_address = true;
        }
        $data = ["api_token" => $business_info->api_token, "business_id" => $business_info->id, "has_address" => $has_address];
        return response()->json(["status" => true, 'data' => $data]);
    }

    function checkDomain(Request $request)
    {
        $domain = $request->input("domain");
        $check_domain = Business::where("domain", $domain)->value("domain");
        if (empty($check_domain)) {
            return response()->json(["status" => true]);
        } else {
            return response()->json(["status" => false]);
        }
    }

    function checkEmail(Request $request)
    {
        $email = $request->input("email");
        $check_email = Business::where("email", $email)->value("email");
        if (empty($check_email)) {
            return response()->json(["status" => true]);
        } else {
            return response()->json(["status" => true]);
        }
    }

    public function pushStore(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);
        if (empty($data)) {
            return response()->json(["status" => false, 'errors' => 'json 格式有错']);
        }
        $business_id = $request->business_info->id;
        try {
            BusinessPushStore::dispatch($business_id, $data)->onConnection('redis')->onQueue("push_store");
        } catch (\Exception $e) {
            return response()->json(["status" => false]);
        }
        return response()->json(["status" => true]);
    }


}
