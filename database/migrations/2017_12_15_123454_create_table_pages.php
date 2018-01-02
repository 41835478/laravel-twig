<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements("id"); // collections 唯一id
            $table->bigInteger("business_id")->index();
            $table->string("title");
            $table->text("description")->nullable();
            $table->text("content")->nullable();
            $table->string("template")->nullable();     //模板值 page page.contact

            $table->string("page_title")->nullable();
            $table->string("meta_description")->nullable();
            $table->string("handle",100)->nullable()->index();

            $table->smallInteger("up_and_down")->default(1); // 1 显示 2 隐藏 3 已删除 在回收站 4 彻底删除不可找回

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
        Schema::dropIfExists('pages');
    }
}
