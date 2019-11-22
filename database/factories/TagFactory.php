<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name'=>$faker->word,
        'description'=>$faker->randomElement([NULL,$faker->paragraph(random_int(5,10))]),
        'user_id'=>1
    ];
    // $table->string('name');
    // $table->mediumText('description')->nullable();


    // $table->unsignedBigInteger('user_id');
});
