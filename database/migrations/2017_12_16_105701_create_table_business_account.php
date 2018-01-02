<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBusinessAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_accounts', function (Blueprint $table) {
            $table->bigIncrements("id"); // 会员在商户的 唯一id
            $table->bigInteger("business_id")->index();
            $table->string("first_name",50);
            $table->string("last_name",50);
            $table->text("email");
            $table->string("password",60);     //模板值 page page.contact
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
        Schema::dropIfExists('business_accounts');
    }
}
