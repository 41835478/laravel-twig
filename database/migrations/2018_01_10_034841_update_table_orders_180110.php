<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableOrders180110 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->decimal("shipping_methods_price")->after("shipping_methods_price_cny") ;
            $table->integer("refund")->default(0)->after("status_msg"); // 0 正常 1 申请退款 2 同意退款 3 退款成功
            $table->string("refund_msg")->nullable()->after("status_msg");
            $table->integer("abnormal")->default(0)->after("status_msg"); // 0 订单正常 1 订单异常
            $table->string("abnormal_msg")->nullable()->after("status_msg");
            $table->string("currency")->after("third_serial_number");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('refund');
            $table->dropColumn('refund_msg');
            $table->dropColumn('abnormal');

            $table->dropColumn('abnormal_msg');
            $table->dropColumn('currency');
            $table->dropColumn('shipping_methods_price');

        });
    }
}
