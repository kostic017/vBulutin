<?php

use Faker\Generator as Faker;

$factory->define(App\Board::class, function (Faker $faker) {
    static $uid = 0;

    $title = rtrim($faker->sentence(6), '.');
    $name = strtok($title, ' ') . $uid;

    return [
        'name' => $name,
        'title' => $title,
        'description' => $faker->optional()->paragraph(),
        'visible' => true,
        'owned_by' => function() {
            return App\User::inRandomOrder()->first()->id;
        }
    ];
});
