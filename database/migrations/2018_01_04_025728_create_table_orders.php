<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements("id"); //
            $table->bigInteger("order_id")->index();
            $table->bigInteger("business_id")->index();
            $table->bigInteger("member_id")->index();
            $table->string("third_serial_number",50)->nullable();
            $table->decimal("total_price_cny");
            $table->decimal("total_price");
            $table->string("remarks")->nullable();
            $table->string("delivery_method"); // 商户默认直发
            //国际物流费用
            $table->integer("sending_route")->nullable();           //国内线路id
            $table->smallInteger("shipping_methods_id")->nullable(); //选择的物流id
            $table->string("shipping_methods_name")->nullable();    //物流名称
            $table->decimal("shipping_methods_price_cny")->nullable();    //物流运费价格人民币 0元 免邮

            $table->smallInteger("account_address_id"); //用户的地址id
            $table->smallInteger("status")->default(0); // 0 未支付订单  1 已成功支付订单
            $table->string("status_msg")->nullable();  //订单文字描述信息
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
        Schema::dropIfExists('orders');
    }
}
