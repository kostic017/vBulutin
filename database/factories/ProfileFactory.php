<?php

use Faker\Generator as Faker;

$factory->define(App\Profile::class, function (Faker $faker, array $args) {
    return [
        'about' => $faker->optional()->paragraph(),
        'birthday_on' => $faker->optional()->date(),
        'sex' => $faker->optional()->randomElement(['m', 'f', 's']),

        'job' => $faker->optional()->jobTitle,
        'name' => $faker->optional()->name,
        'residence' => $faker->optional()->city,
        'birthplace' => $faker->optional()->city,
        'avatar' => $faker->optional()->imageUrl(100, 100),
    ];
});
