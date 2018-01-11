<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->increments("id");
            $table->bigInteger("area_id");
            $table->string("area_en_name",100);
            $table->string("official_eng",100);
            $table->string("countryCode",20);
            $table->string("currency",50);
            $table->string("sign",100);
            $table->text("svg")->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency');
    }
}
