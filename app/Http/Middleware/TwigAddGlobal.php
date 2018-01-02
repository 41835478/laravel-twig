<?php

namespace App\Http\Middleware;

use Closure;
use Twig;
use App\Http\Models\Business;
use Illuminate\Support\Facades\Session;
use App\Http\Models\Collections;
use App\Http\Models\Pages;

class TwigAddGlobal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $base_path= base_path();
        $theme_host = $request->server->get("HTTP_HOST");
       // Session::get("business_info");
        $business = Session::get("business_info");
        if (empty($business)){
            $business = Business::where("domain",$theme_host)->select("id","domain")->first();
            if(empty($business)){
                return redirect('https://www.buckydrop.com/');
            }
            $business = $business->toArray();
            if(Session::has("business_info")){
                Session::remove("business_info");
            }
            Session::put("business_info",$business);
        }
        $business_id = $business["id"];
        $current_theme_dir = $request["current_theme_dir"];
        $setting_data_file = $current_theme_dir."/config/settings_data.json";
        $setting_dat = file_get_contents($setting_data_file);
        $content = json_decode($setting_dat,true);

        $request["setting"] = $content;
        Twig::addGlobal("settings",$content["current"]);
        Twig::addGlobal("sections",$content["current"]["sections"]);

        Twig::addGlobal("assets",$request["assets"]);
        Twig::addGlobal("host","http://".$theme_host);

        // linklists 导航变量的映射
        $collections = Collections::where("business_id",$business["id"])->where("up_and_down",1)->select("id","title")->get();
        $linklists = [];
        if ($collections->isNotEmpty()){
            $collections = $collections->toArray();
            // 获取page 页面
           foreach ($collections as $value){
               $link=["title"=>$value["title"],"url"=>"/collections/{$value["id"]}"];
               $linklists["main-menu"]["links"][]=$link;
           }
        }
        $pages = Pages::where("business_id",$business["id"])->where("up_and_down",1)->select("id","title")->get();
        if ($pages->isNotEmpty()){
            foreach ($pages as $value){
                $page = ["title"=>$value["title"],"url"=>"/pages/{$value["id"]}"];
                $linklists["main-menu"]["links"][] = $page;
            }
        }
        Twig::addGlobal("linklists",$linklists);
        return $next($request);
    }
}
