<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->longText('body');
            $table->unsignedInteger('claps')->default(1);

            
            $table->string('meta_status');
            
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');
            
            $table->foreign('user_id')->references('id')->on('users');
            
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
