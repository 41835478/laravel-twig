<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrdersProductItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_product_items', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("order_id");
            $table->bigInteger("business_id");
            $table->bigInteger("product_id");

            $table->string("spuCode",50);
            $table->string("skuCode",50);
            $table->string("platform",10);
            $table->decimal("price_cny");
            $table->decimal("price_usd");
            $table->integer("quantity");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_product_items');
    }
}
