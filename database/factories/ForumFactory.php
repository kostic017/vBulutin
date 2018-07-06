<?php

use Faker\Generator as Faker;

$factory->define(App\Forum::class, function (Faker $faker, array $args) {
    // Pozicija koju novokreirani forum treba da zauzme unutar
    // odredjenog roditeljskog foruma odnosno kategorije.
    static $positions = [
        'parent_id' => [],
        'category_id' => []
    ];

    $parent_id = $args['parent_id'];
    $category_id = $args['category_id'];

    $title = random_title($faker, 5);

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'description' => $faker->optional()->paragraph(),

        'position' => function() use (&$positions, $category_id, $parent_id) {
            if ($parent_id) {
                if (!array_key_exists($parent_id, $positions['parent_id'])) {
                    $positions['parent_id'][$parent_id] = 1;
                }
                return $positions['parent_id'][$parent_id]++;
            }

            if (!array_key_exists($category_id, $positions['category_id'])) {
                $positions['category_id'][$category_id] = 1;
            }
            return $positions['category_id'][$category_id]++;
        },

        'is_locked' => $faker->randomElement([true, false]),

        'deleted_at' => $faker->optional(0.1)->dateTime()
    ];
});
