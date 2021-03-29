<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Episode;
use Faker\Generator as Faker;

$factory->define(Episode::class, function (Faker $faker) {
    return [
        "number" => $faker->numberBetween(1, 25),
		"title" => $faker->sentence,
		"content" => implode("\n\n", $faker->paragraphs($faker->numberBetween(4, 10))),
		"video_url" => 'https://youtube.com?q=' . Str::random(),
		"video_duration" => $faker->numberBetween(300, 900),
		"video_embed_type" => $faker->randomElement(['public', 'private']),
		"material_file" => 'sample.zip',
		"practice_required" => true,
		"practice_file_mimes" => ['zip'],
		"published" => true,
		"is_free" => true,
		"course_id" => $faker->numberBetween(1, 125),
    ];
});
