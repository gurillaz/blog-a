<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('slug')->unique();
            $table->string('title');
            $table->mediumText('summary');
            $table->longText('body');
            $table->string('image_path');

            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');

            $table->dateTime('publishing_date');

            $table->unsignedInteger('claps')->default(1);

            $table->string('meta_status');
            $table->string('meta_is-feature')->nullable();
            $table->string('meta_open-new-window')->nullable();
            $table->unsignedInteger('meta_list_place')->nullable();


            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('articles');
    }
}
