<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BusinessAddress extends TestCase
{

    public function testIndex()
    {
        $response = $this->get($this->domain.'/api/business/address');
        $response->assertSuccessful();
    }

    public function testCreate(){

    }

    public function testStore(){

    }

}
