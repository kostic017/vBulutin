<?php

use Faker\Generator as Faker;

$factory->define(App\Topic::class, function (Faker $faker, $args) {
    return [
        'title' => rtrim($faker->sentence(6), '.'),
        'is_locked' => $faker->randomElement([true, false]),
        'deleted_at' => $faker->optional(0.1)->dateTime()
    ];
});
