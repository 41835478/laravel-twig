<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use App\Http\Helper\Helper;

use Illuminate\Support\Facades\File ;

class BusinessConfigSettings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $business_id ;
    private $domain ;
    /**
     * Create a new job instance.
     * @param $business_id
     * @param $domain
     * @return void
     */
    public function __construct($business_id,$domain)
    {
        $this->business_id = $business_id;
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $default_theme_id = DB::table("themes")->where("default",1)->value("id");

        $check_business_theme = DB::table("business_themes")->where("business_id".$this->business_id)->where("theme_id",$default_theme_id)->first();
        if(!empty($check_business_theme)){
            Helper::getLog("business_config_settings")->info("默认模板已经设置 直接返回");
            return true;
        }
        $date_time =date("Y-m-d H:i:s");
        $directory = $this->domain.".buckydrop.com";
        $data = ["business_id"=>$this->business_id,"theme_id"=>$default_theme_id,"directory"=>$directory,"enabled"=>1,"created_at"=>$date_time,"updated_at"=>$date_time];
        DB::table("business_themes")->insert($data);

        // 从模板商店目录 copy一份模板过来

        $template_path = config("app.template_path");

        $default_theme_path = $template_path. "/theme_store/".$default_theme_id ;

        $copy_source_path = $template_path."/theme/".$directory ;

        File::copyDirectory($default_theme_path,$copy_source_path);

        //默认插入美元的货币
        DB::table("business_settings")->insert(["business_id"=>$this->business_id,"area_id"=>3,"currency"=>"USD","currency_format"=>"$ {{amount}} USD"]);


    }
}



