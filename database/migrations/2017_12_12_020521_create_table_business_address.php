<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBusinessAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_address', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger("business_id");
            $table->string("first_name",50);
            $table->string("last_name",50);
            $table->text("street_address");
            $table->text("suite");

            $table->integer("country_id")->nullable();
            $table->integer("state_id")->default(0)->nullable();
            $table->integer("city_id")->nullable();
            
            $table->string("phone_number",20);
            $table->string("website",50)->nullable();
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
    }
}
