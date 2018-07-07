<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'remember_token' => str_random(10),
        'email' => $faker->unique()->safeEmail,
        'username' => $faker->unique()->username,
        'is_invisible' => $faker->randomElement([true, false]),
        'email_token' => $faker->randomElement([true, false]) ? null : str_random(10),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret

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
