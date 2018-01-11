<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Swap\Builder;

class TestController extends Controller
{

    public function index(){
        $swap = (new Builder())
            ->add('fixer')
            ->build();

// Get the latest EUR/USD rate
        $rate = $swap->latest('USD/CNY');

// 1.129
        var_dump($rate->getValue());

    }
}
