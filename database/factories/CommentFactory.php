<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'body'=>$faker->paragraph(random_int(2,20)),
        'claps'=>random_int(1,30),
        'meta_status'=>$faker->randomElement(['pending','published','deleted']),
        'user_id'=>1
    ];
});
