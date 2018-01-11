<?php

namespace App\Http\Models;

use Moloquent;

class MongoProducts extends Moloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    public static function getProducts($business_id,$product_id_list){

    }


}
