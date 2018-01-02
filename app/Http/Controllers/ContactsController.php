<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Contacts;


class ContactsController extends Controller
{
    function store(Request $request){
        $request_all = $request->session()->all();
        $_previous = $request_all["_previous"]["url"];
        $contact = $request->input("contact");
        if (empty($contact["email"])){
            return redirect($_previous."#contact_form")->with('errors', 'email is empty');
        }
        $data = [];
        try{
            $business_info = $request_all["business_info"];
            $data["business_id"] = $business_info["id"];
            $data["email"]= $contact["email"];
            $data["name"] = $contact["name"];
            $data["phone_number"] = $contact["phone-number"];
            $data["message"] = $contact["body"];
            $date_time = date("Y-m-d H:i:s");
            $data["created_at"] = $date_time;
            $data["updated_at"] = $date_time;
            Contacts::insert($data);
        }catch (\Exception $e){
            return redirect($_previous."#contact_form")->with('errors', "Server Error Please try again later");
        }
        $success = "Thanks for contacting us. We'll get back to you as soon as possible.";
        return redirect($_previous."#contact_form")->with('success', $success);
    }
}
