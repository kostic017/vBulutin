<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker, $args) {
    return [
        "content" => $faker->paragraph(),
        "deleted_at" => $faker->optional(0.1)->dateTime(),
        "user_id" => function() {
            return App\User::inRandomOrder()->first()->id;
        }
    ];
});
