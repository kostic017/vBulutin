<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker, $args) {
    return [
        'content' => $faker->paragraph(),
        'deleted_at' => function() use ($args) {
            $topic = App\Topic::withTrashed()->findOrFail($args['topic_id']);
            return $topic->posts()->count() > 0 ? $faker->optional(0.1)->dateTime() : null;
        },
        'user_id' => function() {
            return App\User::inRandomOrder()->first()->id;
        }
    ];
});
