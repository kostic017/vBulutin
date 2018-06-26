<?php

use Faker\Generator as Faker;

$factory->define(App\Topic::class, function (Faker $faker, array $args) {
    static $id = 1;
    $title = random_title($faker, 5, false);

    return [
        'title' => $title,
        'slug' => unique_slug($title, $id++),
        'deleted_at' => $faker->optional(0.1)->dateTime(),
        'is_locked' => $faker->randomElement([true, false]),
    ];
});
