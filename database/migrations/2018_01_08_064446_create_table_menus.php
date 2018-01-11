<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements("id") ;
            $table->bigInteger("business_id"); //属于哪个商户的
            $table->bigInteger("navigation_id");

            $table->string("title",50);

            $table->string("menu_type",10); // 1 home 2 search 3 collection 4 product 5 page 6 blog
            $table->string("menu_url",50); // 根据不同的类型生成的 url 地址

            $table->bigInteger("subject_id");  // 表示相应 按钮类型的 id
            $table->smallInteger("position");
            $table->bigInteger("parent_menu_id");
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
        Schema::dropIfExists('menus');
    }
}
