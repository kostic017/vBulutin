<?php

use Faker\Generator as Faker;

$factory->define(App\Section::class, function (Faker $faker) {

    static $position = 1;

    return [
        "title" => $faker->sentence(6),
        "description" => $faker->paragraph(),
        "position" => $position++
    ];

});
