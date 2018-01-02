<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBlogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('blogs', function (Blueprint $table) {
            $table->increments("id");
            $table->bigInteger("business_id");
            $table->string("title");
            $table->string("page_title");
            $table->string("meta_description");
            $table->string("handle",100)->index();
            $table->smallInteger("comments");
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
