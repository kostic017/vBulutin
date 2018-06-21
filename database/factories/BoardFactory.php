<?php

use Faker\Generator as Faker;

$factory->define(App\Board::class, function (Faker $faker) {
    static $id = 0;
    $title = rtrim($faker->sentence(6), '.');

    return [
        'title' => $title,
        'url' => strtok($title, ' ') . $id++,
        'description' => $faker->optional()->paragraph(),
        'is_visible' => true,
        'owner_id' => function() {
            $owner = App\User::inRandomOrder()->first();
            $owner->email_token = null;
            $owner->save();
            return $owner->id;
        }
    ];
});
