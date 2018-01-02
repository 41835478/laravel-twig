<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\BusinessAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class BusinessAddressController extends Controller
{
    /**
     * 获取用户设置地址信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $business_info = $request->business_info;
        $business_id = $business_info->id;
        $data = BusinessAddress::where("business_id", $business_id)->first();
        if (empty($data)) {
            $data = null;
        }
        unset($data->id);
        return response()->json(["status" => true, 'data' => $data]);
    }


    public function create(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);
        if (empty($data)) {
            return response()->json(["status" => false, 'errors' => 'json 格式有错']);
        }
        $messages = [
            'first_name.required' => 'first_name not empty',
            'last_name.required' => 'last_name not empty',
            'street_address.required' => 'street_address not empty',
            'suite.required' => 'suite not empty',
            'phone_number.required' => "phone_number not empty"
        ];

        $validator = Validator::make($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'street_address' => 'required',
            'suite' => 'required',
            'phone_number' => 'required',
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

        $business_info = $request->business_info;
        $business_id = $business_info->id;
        $country_id = isset($data["country_id"]) ? $data["country_id"] : 0;
        $state_id = isset($data["state_id"]) ? $data["state_id"] : 0;
        $city_id = isset($data["city_id"]) ? $data["city_id"] : 0;
        $website = isset($data["website"]) ? $data["website"] : "";
        $result = ["first_name" => $data["first_name"], "last_name" => $data["last_name"], "street_address" => $data["street_address"], "suite" => $data["suite"]];
        $result["phone_number"] = $data["phone_number"];
        $result["business_id"] = $business_id;
        $result["country_id"] = $country_id;
        $result["state_id"] = $state_id;
        $result["city_id"] = $city_id;
        $result["website"] = $website;
        $result["created_at"] = date("Y-m-d H:i:s");
        $result["updated_at"] = date("Y-m-d H:i:s");
        try {
            BusinessAddress::updateOrInsert(["business_id" => $business_id], $result);
        } catch (\Exception $e) {
            return response()->json(["status" => false, 'errors' => "Server error try again later"]);
        }
        return response()->json(["status" => true, "data" => $result]);
    }

}
