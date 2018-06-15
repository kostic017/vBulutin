<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    $confirmed = $faker->randomElement([true, false]);

    return [
        'username' => $faker->unique()->username,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret

        'email' => $faker->unique()->safeEmail,
        'email_token' => $confirmed ? null : str_random(10),

        'is_invisible' => $faker->randomElement([true, false]),

        'remember_token' => str_random(10)
    ];
});
