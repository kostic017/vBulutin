<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    static $id = 1;
    static $position = 1;

    $title = rtrim($faker->sentence(6), '.');

    return [
        'title' => $title,
        'slug' => unique_slug($title, $id++),

        'description' => $faker->optional()->paragraph(),
        'position' => $position++,
        'deleted_at' => $faker->optional(0.1)->dateTime()
    ];

});
