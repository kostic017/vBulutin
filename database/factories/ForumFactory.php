<?php

use Faker\Generator as Faker;

$factory->define(App\Forum::class, function (Faker $faker, array $args) {
    static $positions = [
        'parent_id' => [],
        'category_id' => []
    ];

    $parentId = $args['parent_id'];
    $categoryId = $args['category_id'];

    return [
        'title' => rtrim($faker->sentence(6), '.'),
        'description' => $faker->optional()->paragraph(),

        'position' => function() use (&$positions, $categoryId, $parentId) {
            if ($parentId) {
                if (!array_key_exists($parentId, $positions['parent_id'])) {
                    $positions['parent_id'][$parentId] = 1;
                }
                return $positions['parent_id'][$parentId]++;
            }

            if (!array_key_exists($categoryId, $positions['category_id'])) {
                $positions['category_id'][$categoryId] = 1;
            }
            return $positions['category_id'][$categoryId]++;
        },

        'is_locked' => $faker->randomElement([true, false]),

        'deleted_at' => $faker->optional(0.1)->dateTime()
    ];

});
