<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    static $position = 1;
    $title = random_title($faker, 5);

    return [
        'title' => $title,
        'position' => $position++,
        'slug' => str_slug($title),
        'description' => $faker->optional()->paragraph(),
        'deleted_at' => $faker->optional(0.1)->dateTime()
    ];
});
