<?php

use Faker\Generator as Faker;

$factory->define(App\Forum::class, function (Faker $faker, $args) {
    static $positions = [
        "parent_id" => [],
        "section_id" => []
    ];

    $parentId = $args["parent_id"];
    $sectionId = $args["section_id"];

    return [
        "title" => $faker->sentence(6),
        "description" => $faker->optional()->paragraph(),

        "position" => function() use (&$positions, $sectionId, $parentId) {
            if ($parentId) {
                if (!array_key_exists($parentId, $positions["parent_id"])) {
                    $positions["parent_id"][$parentId] = 1;
                }
                return $positions["parent_id"][$parentId]++;
            }

            if (!array_key_exists($sectionId, $positions["section_id"])) {
                $positions["section_id"][$sectionId] = 1;
            }
            return $positions["section_id"][$sectionId]++;
        },

        "is_locked" => $faker->randomElement([true, false]),

        "deleted_at" => $faker->optional(0.1)->dateTime()
    ];

});
