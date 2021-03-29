<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Course;
use App\Models\Episode;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        "title" => $faker->sentence,
		"description" => $faker->paragraph,
		"price" => $faker->numberBetween(59, 179),
		"importable" => false,
		"archived" => false,
		"user_id" => $faker->uuid,
    ];
});

$factory->afterCreating(Course::class, function ($course, $faker) {
	$items = [];

	for ($i=1; $i <= $faker->numberBetween(7, 18); $i++) { 
		array_push(
			$items, 
			factory(Episode::class)->make([
				'number' => $i,
				'video_embed_type' => 'private',
				'is_free' => false,
				'user_id' => $course->user_id
			])
		);
	}

	$course->episodes()->saveMany($items);
});
