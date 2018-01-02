<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCountry()
    {
        $this->withoutMiddleware();
        $response = $this->get($this->domain.'/api/geo/country');
        $response->assertSuccessful();
        //$response->dump();
    }


    public function testState(){
        $this->withoutMiddleware();
        $response = $this->get($this->domain.'/api/geo/state?country_id=45');
        $response->assertSuccessful();
        //$response->dump();
    }

    public function testCity(){
        $this->withoutMiddleware();
        $response = $this->get($this->domain.'/api/geo/city?state_id=9');
        $response->assertSuccessful();
        $response->dump();
    }

}
