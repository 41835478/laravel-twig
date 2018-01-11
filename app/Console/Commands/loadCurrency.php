<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Countries;


class loadCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:load_currency';

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
        $contry_list = DB::table("areas")->where("deep",2)->select("areaId","areaEnName","countryCode")->get();

        $currency_list = ["USD","CAD","CNY"];
        $i = 1 ;
        foreach ($contry_list as $key=>$value){

            $t = Countries::where('cca2', $value->countryCode);

            $content = array_first($t);

            $official_eng = isset($content->name->native->eng)?$content->name->native->eng->official:$content->name->official;

            $currency = array_first($content->currency);

            $ISO4217Code = $currency["ISO4217Code"];
            if(!in_array($ISO4217Code,$currency_list)){
                continue ;
            }
            $sign = $currency["sign"];

            $svg = strlen($content->flag->svg) < 3000 ? $content->flag->svg :"";

            $data = [
                "area_id"=>$value->areaId,"area_en_name"=>$value->areaEnName,"official_eng"=>$official_eng,
                "countryCode"=>$value->countryCode,
                "currency"=>$ISO4217Code,
                "sign"=>$sign,
                "svg"=>$svg
            ];
            DB::table("currency")->insert($data);
            $this->info("成功 ". $i++);
        }
    }
}
