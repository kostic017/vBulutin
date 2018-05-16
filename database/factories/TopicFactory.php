<?php

use Faker\Generator as Faker;

$factory->define(App\Topic::class, function (Faker $faker, array $args) {
    static $id = 1;

    $title = rtrim($faker->sentence(6), '.');

    return [
        'title' => $title,
        'slug' => unique_slug($title, $id++),
        'is_locked' => $faker->randomElement([true, false]),
        'deleted_at' => $faker->optional(0.1)->dateTime()
    ];
});
