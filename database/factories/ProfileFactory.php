<?php

use Faker\Generator as Faker;

$factory->define(App\Profile::class, function (Faker $faker, array $args) {
    return [
        'name' => $faker->optional()->name,
        'job' => $faker->optional()->jobTitle,
        'residence' => $faker->optional()->city,
        'birthplace' => $faker->optional()->city,
        'about' => $faker->optional()->paragraph(),
        'birthday_on' => $faker->optional()->date(),
        'sex' => $faker->optional()->randomElement(['m', 'f', 'o']),
        'avatar' => $faker->randomElement([null, config('custom.random_image')]),
    ];
});
