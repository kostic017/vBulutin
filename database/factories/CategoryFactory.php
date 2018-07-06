<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker, $args) {
    $title = random_title($faker, 5);

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'description' => $faker->optional()->paragraph(),
        'deleted_at' => $faker->optional(0.1)->dateTime(),

        'position' => function () use ($args) {
            return App\Board::findOrFail($args['board_id'])->categories()->max('position') + 1;
        },
    ];
});
