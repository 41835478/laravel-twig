<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Pages;


class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('twig_add_global');
    }

    /**
     * 单页的 映射对象
     * @param Request $request
     * @param $page_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show(Request $request , $page_id){
        $business_info = $request->session()->get("business_info");
        $business_id = $business_info["id"];

        $page =Pages::where("business_id",$business_id)->where("id",$page_id)->select("id","title","content","template","updated_at")->first();
        if (empty($page)){
            return redirect("/");
        }
        $page= $page->toArray();
        $page["template_suffix"] = strpos($page["template"],"contact") !== false ? "contact":"";
        $page["url"] = "/pages/{$page["id"]}" ;
        return view("page",["page"=>$page]);
    }

}
