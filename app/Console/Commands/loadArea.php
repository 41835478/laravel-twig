<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class loadArea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:load_area';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入地址信息';

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
        $area_data = file_get_contents("http://testapi.oa.com/logistics/cache/areas");

        $data = json_decode($area_data,true);
        $data =$data["data"];
        $count = count($data);
        $this->info("获取数据 多少条 ",$count);
        $j = 1;
        foreach ($data as $key=>$value){
            DB::table("areas")->insert($value);
            $j++;
        }
        $this->info("成功多少条 {$j}");
    }

}
