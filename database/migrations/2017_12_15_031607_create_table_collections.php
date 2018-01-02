<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->bigIncrements("id"); // collections 唯一id
            $table->bigInteger("business_id")->index();
            $table->string("title");
            $table->text("description")->nullable();
            $table->string("cover_images")->nullable();     //封面图
            $table->string("page_title")->nullable();
            $table->string("meta_description")->nullable();
            $table->string("handle",100)->nullable()->index();
            $table->smallInteger("up_and_down")->default(1); // 1 上架 2 下架 3 已删除 在回收站 4 彻底删除不可找回
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
        Schema::dropIfExists('collections');
    }
}
