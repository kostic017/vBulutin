<?php

use Faker\Generator as Faker;

$factory->define(App\Directory::class, function (Faker $faker) {
    static $id = 0;
    $title = rtrim($faker->sentence(2), '.');

    return [
        'title' => $title,
        'slug' => unique_slug($title, $id++),
        'description' => $faker->optional()->paragraph(),
    ];
});
