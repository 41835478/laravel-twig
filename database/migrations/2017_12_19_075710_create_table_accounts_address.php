<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAccountsAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_address', function (Blueprint $table) {
            $table->bigIncrements("id"); //
            $table->bigInteger("account_id")->index(); //会员账号id
            $table->string("first_name",50)->nullable();
            $table->string("last_name",50)->nullable();
            $table->string("company",50)->nullable();
            $table->text("address1")->nullable();
            $table->text("address2")->nullable();
            $table->string("city");
            $table->smallInteger("country_id");
            $table->string("country_name");

            $table->smallInteger("province_id");
            $table->string("province_name");
            $table->string("zip_code",10);
            $table->string("phone_number")->nullable();
            $table->smallInteger("default")->default(0) ; // 1 默认 0 未默认
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
        Schema::dropIfExists('accounts_address');
    }
}
