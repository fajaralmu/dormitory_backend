<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Study;
use App\Models\Lesson;
use Faker\Generator as Faker;

$factory->define(Study::class, function (Faker $faker) {
    return [
        "title" => $faker->sentence,
		"description" => $faker->paragraph,
		"price" => $faker->numberBetween(59, 179),
		"importable" => false,
		"archived" => false,
		"user_id" => $faker->uuid,
		"mapel_id" => $faker->uuid,
    ];
});

$factory->afterCreating(Study::class, function ($study, $faker) {
	$items = [];

	for ($i=1; $i <= $faker->numberBetween(7, 18); $i++) { 
		array_push(
			$items, 
			factory(Lesson::class)->make([
				'number' => $i,
				'video_embed_type' => 'private',
				'is_free' => false,
				'user_id' => $study->user_id
			])
		);
	}

	$study->lessons()->saveMany($items);
});
