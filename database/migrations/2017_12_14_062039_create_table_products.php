<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements("id"); //作为系统的内部唯一的商品id
            $table->bigInteger("business_id");
            $table->smallInteger("platform"); // 产品平台 0 未知 1:淘宝;2:天猫;3京东；10:淘宝联盟 11 商户自己添加产品
            $table->string("platform_name",20); // 平台内容 1 TB 2 TMALL 3 JD
            $table->bigInteger("spu_code");        //第三方的spu_code
            $table->text("product_name");
            $table->text("product_title")->nullable();
            $table->text("spu_imgs")->nullable();
            $table->smallInteger("up_and_down")->default(1); // 1 表示上架
            $table->dateTime("update_time"); // 商品最后的更新时间
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
