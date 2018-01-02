<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableAccountsAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts_address', function (Blueprint $table) {
            $table->string("city")->nullable()->change();
            $table->integer("country_id")->nullable()->change();
            $table->string("country_name")->nullable()->change();
            $table->integer("province_id")->nullable()->change();
            $table->string("province_name")->nullable()->change();
            $table->string("zip_code",10)->nullable()->change();
            $table->string("phone_number")->nullable()->change();
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
