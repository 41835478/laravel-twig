<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Http\Models\MongoProducts;

class loadProductPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:get_product_price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $product_list = DB::table("products")->select("id","business_id")->get();
        $j = 1;
        foreach ($product_list as $value){
            $skus =MongoProducts::where("business_id",$value->business_id)->where("product_id",$value->id)->value("skus");
            if(is_array($skus) && !empty($skus)){
                $j ++;
                $sku_price = isset($skus[0]["sellPrice"])?$skus[0]["sellPrice"]/100:"0";
                DB::table("products")->where("id",$value->id)->update(["price"=>$sku_price]);
                $this->info($sku_price);
            }
        }

    }
}
