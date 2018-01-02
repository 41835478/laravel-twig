<?php

namespace App\Http\Models;

use Moloquent;

class MongoProducts extends Moloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

}
