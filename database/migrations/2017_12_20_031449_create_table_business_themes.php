<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBusinessThemes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_themes', function (Blueprint $table) {
            $table->bigIncrements("id"); //
            $table->bigInteger("business_id")->index(); //会员账号id
            $table->bigInteger("theme_id");
            $table->string("directory"); //一般为用户设置的域名
            $table->smallInteger("enabled")->default(0);  // 1 启用 0 禁用
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
        Schema::dropIfExists('business_themes');
    }
}
