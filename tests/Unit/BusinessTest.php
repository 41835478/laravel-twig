<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BusinessTest extends TestCase
{
    public $domain = "http://china.buckydrop.com/";
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegisterTest()
    {
        $this->withoutMiddleware();
        // 创建订单 -支付宝-当面付-二维码扫描支付
        $data = array("email" => "346006742@qq.com", "password" => 123456,"domain"=>"test1.buckydrop.com");
        $response = $this->postJson($this->domain.'/api/business/create', $data,[]);
        $response->dump();
        $this->assertTrue(true);
        $response->assertSuccessful();
        $response->assertStatus(200);
    }


    public function testLoginTest(){
        $this->withoutMiddleware();
        $data = array("email" => "346006742@qq.com", "password" => 123456);
        $response = $this->postJson($this->domain.'/api/business/login', $data,[]);
        $this->assertTrue(true);
        $response->assertSuccessful();
        $response->assertStatus(200);
        $response->dump();
    }


}
