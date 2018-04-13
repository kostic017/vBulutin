<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    static $position = 1;

    return [
        'title' => $faker->sentence(6),
        'description' => $faker->optional()->paragraph(),
        'position' => $position++,
        'deleted_at' => $faker->optional(0.1)->dateTime()
    ];

});
