<?php

use Faker\Generator as Faker;

$factory->define(App\Board::class, function (Faker $faker) {
    static $id = 0;
    $title = rtrim($faker->sentence(6), '.');

    return [
        'title' => $title,
        'name' => strtok($title, ' ') . $id++,
        'description' => $faker->optional()->paragraph(),
        'is_visible' => true,
        'owned_by' => function() {
            return App\User::inRandomOrder()->first()->id;
        }
    ];
});
