<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class loadRegion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:load_region';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'load 地区库到mysql中';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $xmlstring = file_get_contents(public_path().DIRECTORY_SEPARATOR."LocList.xml");
        $xml = simplexml_load_string($xmlstring);
        $json = json_encode($xml);
        $array = json_decode($json);
        $country_region = $array->CountryRegion;
        $attributes = "@attributes";
        $state_key = "State";
        $city_key = "City";
        DB::table("country")->truncate();
        DB::table("state")->truncate();
        DB::table("city")->truncate();

        foreach ($country_region as $key=>$value){
            $country_attr = $value->$attributes;
            $country_name = $country_attr->Name;
            $country_code = $country_attr->Code;
            $country_id = DB::table("country")->insertGetId(["name"=>$country_name,"code"=>$country_code]);
            $this->info("国家信息入库成功".$country_id);
            if (isset($value->$state_key)){
                $state = $value->$state_key ;
                if (is_array($state)){
                    foreach ($state as $val){
                        $state_info = $val->$attributes;
                        $state_name = $state_info->Name;
                        if (!isset($val->$city_key)){
                            continue;
                        }
                        $city = $val->$city_key;
                        $state_id = DB::table("state")->insertGetId(["name"=>$state_name,"country_id"=>$country_id]);
                        if (is_array($city)){
                            foreach ($city as $v){
                                $city_info = $v->$attributes;
                                $city_name = $city_info->Name;
                                DB::table("city")->insert(["name"=>$city_name,"country_id"=>$country_id,"country_name"=>$country_name,"state_id"=>$state_id,"state_name"=>$state_name]);
                            }
                        }
                    }
                }else{
                    $city = $state->$city_key;
                    if (!empty($city)){
                        foreach ($city as $key=>$val){
                            $city_info = $val->$attributes;
                            $city_name = $city_info->Name;
                            DB::table("city")->insert(["name"=>$city_name,"country_id"=>$country_id,"country_name"=>$country_name,"state_id"=>0,"state_name"=>""]);
                        }
                    }
                }
            }else{
                $this->info("没有地区的国家");
            }

        }
    }
}
