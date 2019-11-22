<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name'=>$faker->word,
        'description'=> $faker->randomElement([NULL,$faker->paragraph(random_int(5,10))]),
        'user_id'=>1
    ];

    // $table->bigIncrements('id');

    // $table->string('name');
    // $table->mediumText('description')->nullable();


    // $table->unsignedBigInteger('user_id');

    // $table->foreign('user_id')->references('id')->on('users');
    
    
    // $table->softDeletes();
});
