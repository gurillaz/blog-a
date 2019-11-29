<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Image;

$factory->define(Article::class, function (Faker $faker) {

    $title = $faker->sentence(6, true);

    $faker_image = $faker->image('public\\storage\\images\\', $width = 1280, $height = 720, null, false);
    $image = Image::make(file_get_contents(public_path() . "\\storage\\images\\" . $faker_image));

    // dd(Image::make(file_get_contents(public_path().'\\watermark_mask.png')));
    $watermark_mask = Image::make(file_get_contents(public_path() . '\\watermark_mask.png'));



    $image->insert($watermark_mask, "center")
        ->save("public\\storage\\images\\" . Str::slug($title, '-') . ".jpg");
    // Thumbnail
    $thumbnail = Image::Make(file_get_contents(public_path() . "\\storage\\images\\" . $faker_image))
        ->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        })
        ->save("public\\storage\\images\\" . Str::slug($title, '-') . "_thumbnail.png");

    // dd(Storage::delete(public_path()."\\storage\\images\\".$faker_image));
    if (File::exists(public_path() . "\\storage\\images\\" . $faker_image)) {
        // dd("File exists");
        File::delete(public_path() . "\\storage\\images\\" . $faker_image);
    }



    return [
        'slug' => Str::slug($title, '-'),
        'title' => $title,
        'summary' => $faker->paragraph(random_int(15, 25)),
        'body' => $faker->paragraph(random_int(40, 70)),
        'image_path' => Str::slug($title, '-') . ".jpg",
        'publishing_date' => $faker->dateTimeBetween('- 90 days', 'now'),

        'meta_status' => $faker->randomElement(['pending', 'published', 'deleted']),
        'meta_is-feature' => $faker->randomElement(['true', 'false']),
        'meta_open-new-window' => $faker->randomElement(['true', 'false']),
        'user_id' => 1
    ];





    // $table->unsignedBigInteger('category_id');
    // $table->unsignedBigInteger('user_id');

    // $table->dateTime('publishing_date');

    // $table->unsignedInteger('claps')->default(1);

    // $table->string('meta_status');
    // $table->string('meta_is-feature')->nullable();
    // $table->string('meta_open-new-window')->nullable();
    // $table->string('meta_list_place')->nullable();
});
