<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('articles');
        Schema::create('articles', function (Blueprint $table) {
            $table->increments("id");
            $table->bigInteger("business_id");
            $table->string("title");
            $table->text("content");
            $table->text("excerpt")->nullable();

            $table->string("page_title");
            $table->string("meta_description")->nullable();
            $table->string("handle",100)->index();

            $table->string("author",30);
            $table->bigInteger("blog_id");
            $table->smallInteger("visibility");
            $table->string("featured_image")->nullable();
            $table->string("tags")->nullable();
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
