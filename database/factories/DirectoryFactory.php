<?php

use Faker\Generator as Faker;

$factory->define(App\Directory::class, function (Faker $faker) {
    static $id = 0;
    $title = random_title($faker, 2);

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'description' => $faker->optional()->paragraph(),
    ];
});
