<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBusinessFreight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_freights', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("business_id")->index();
            $table->bigInteger("country_id");
            $table->string("country_name",100);

            $table->smallInteger("shipping_methods_id")->nullable(); //选择的物流id
            $table->string("shipping_methods_name")->nullable();    //物流名称

            $table->bigInteger("basic_weight")->nullable();
            $table->decimal("basic_freight_rate")->nullable();

            $table->bigInteger("additional_weight")->nullable();
            $table->decimal("additional_weight_rate")->nullable();
            $table->decimal("fees")->default(0.00)->nullable();
            $table->smallInteger("free")->default(0); // 0 非免邮 1 免邮
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
        //
        Schema::dropIfExists('business_freights');
    }
}
