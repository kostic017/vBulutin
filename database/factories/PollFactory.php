<?php

use Faker\Generator as Faker;

$factory->define(App\Poll::class, function (Faker $faker) {
    return [
        'question' => random_title($faker, 5) . '?',
        'is_multi' => $faker->randomValue([true, false])
    ];
});
