<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Business;
use App\Http\Models\AccountsAddress;
use DB;
use App\Http\Models\Areas;
use Illuminate\Http\Response;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }

    function index(Request $request){
        $country_list = Areas::where("deep",2)->orderby("areaEnName","asc")->pluck("areaEnName","areaId");
        $users = $request->session()->get("users");
        if (empty($users)){
            return redirect('/');
        }

        $account_id = $users["member_id"];
        $address_list = AccountsAddress::getAddress($account_id);

        return view("addresses",["country_list"=>$country_list,"address_list"=>$address_list]);
    }


    public function store(Request $request){
        $users = $request->session()->get("users");
        if (empty($users)){
            return redirect('/');
        }
        $account_id = $users["member_id"];

        $address = $request->input("address");
        $data = AccountsAddress::formatData($account_id,$address);

        $data["created_at"] = date("Y-m-d H:i:s");
        $data["default"] = isset($address["default"])?$address["default"]:0;
        try {
            $address_id = AccountsAddress::insertGetId($data);
            if(isset($address["default"]) && $address["default"] == 1){
                AccountsAddress::setDefault($account_id,$address_id);
            }

        } catch (\Exception $e) {

        }
        return redirect('/account/addresses')->with('success', 'Add address successfully');
    }


    public function update(Request $request,$address_id){
        $users = $request->session()->get("users");
        if (empty($users)){
            return redirect('/');
        }

        $account_id = $users["member_id"];
        $address = $request->input("address");
        $data = AccountsAddress::formatData($account_id,$address);

//        dd($data);
        try {
             AccountsAddress::where("account_id",$account_id)->where("id",$address_id)->update($data);
             if(isset($data["default"]) && $data["default"] == 1){
                 AccountsAddress::setDefault($account_id,$address_id);
             }
        } catch (\Exception $e) {

        }
        $return_url = $request->input("return_url","");
        if(!empty($return_url)){
            return redirect($return_url);
        }
        return redirect('/account/addresses')->with('success', 'Add address successfully');


    }

    /**
     *
     * @param Request $request
     * @param $address_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request,$address_id){
        $users = $request->session()->get("users");
        if (empty($users)){
            return redirect('/');
        }
        $account_id = $users["member_id"];

        try{
            AccountsAddress::where("account_id",$account_id)->where("id",$address_id)->delete();
        }catch (\Exception $e){

        }
        if($request->ajax()){
            return response()->json(["status" => true]);
        }
        return redirect('/account/addresses')->with('success', 'Add address successfully');

    }


}
