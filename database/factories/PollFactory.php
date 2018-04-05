<?php

use Faker\Generator as Faker;

$factory->define(App\Poll::class, function (Faker $faker) {
    return [
        "question" => $faker->sentence(6),
        "is_multy" => $faker->randomValue([true, false])
    ];
});