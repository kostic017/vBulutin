<?php

use Faker\Generator as Faker;

$factory->define(App\Board::class, function (Faker $faker) {
    $title = random_title($faker, 5, false);

    return [
        'title' => $title,
        'is_visible' => true,
        'url' => str_slug($title),
        'description' => $faker->optional()->paragraph(),

        'owner_id' => function() {
            $owner = App\User::inRandomOrder()->first();
            $owner->email_token = null;
            $owner->save();
            return $owner->id;
        }
    ];
});
