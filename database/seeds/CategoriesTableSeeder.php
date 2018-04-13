<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    const CATEGORY_COUNT = 3;
    const FORUM_COUNT = 4;
    const CHILD_COUNT = 2;
    const TOPIC_COUNT = 5;
    const POST_COUNT = 10;
    const USER_COUNT = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sekcijama dodajem forume.
        // Forumima dodajem potforume i teme.
        // Temama dodajem postove. Za svaki post
        // biram jednog od ponudjenih korisnika.

        $users = factory(App\User::class, self::USER_COUNT)->create();
        $categories = factory(App\Category::class, self::CATEGORY_COUNT)->create();

        $categories->each(function ($category) use (&$users) {
            $forums = factory(App\Forum::class, self::FORUM_COUNT)->create([
                "parent_id" => null,
                "category_id" => $category->id
            ]);

            $forums->each(function ($forum) use (&$category) {
                $this->createTopics($forum);

                $children = factory(App\Forum::class, self::CHILD_COUNT)->create([
                    "category_id" => $category->id,
                    "parent_id" => $forum->id
                ]);

                $children->each(function ($child) {
                    $this->createTopics($child);
                });
            });

        });
    }

    private function createTopics($forum) {
        $topics = factory(App\Topic::class, self::TOPIC_COUNT)->create([
            "forum_id" => $forum->id
        ]);

        $topics->each(function ($topic) {
            $posts = factory(App\Post::class, self::POST_COUNT)->create([
                "topic_id" => $topic->id
            ]);
        });
    }
}
