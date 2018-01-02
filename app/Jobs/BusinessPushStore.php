<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Http\Models\Products;
use App\Http\Models\MongoProducts;

class BusinessPushStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $business_id = null;

    private $product_data = [];
    /**
     * Create a new job instance.
     * @param $business_id
     * @param $product_data
     * @return void
     */
    public function __construct($business_id=null,$product_data)
    {
        $this->business_id =$business_id;
        $this->product_data = $product_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }
}
