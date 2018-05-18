<?php

use App\User;
use App\Post;
use App\Topic;
use App\Forum;
use App\Profile;
use App\Category;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
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
        // Napravim gomilu korisnika i za svakog vezem po jedan profil.
        $users = factory(User::class, self::USER_COUNT - 1)->create();

        $users[] = User::create([
            'username' => 'zoki',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'email' => 'gmail@zoki.com',
            'email_token' => null,
            'is_confirmed' => true,
            'is_admin' => true,
            'is_invisible' => false,
            'remember_token' => str_random(10)
        ]);

        $users->each(function ($user) {
            factory(Profile::class, 1)->create(['user_id' => $user->id]);
        });

        // Dodam neke kategorije. Svakoj kategoriji dodam neke forume i potforume.
        // Svakom forumu dodam gomilu tema sa postovima koje vezem za random korisnike.
        $categories = factory(Category::class, self::CATEGORY_COUNT)->create();

        $categories->each(function ($category) use (&$users) {
            $data = [
                'parent_id' => null,
                'category_id' => $category->id
            ];

            if ($category->deleted_at) {
                $data['deleted_at'] = $category->deleted_at;
            }

            $forums = factory(Forum::class, self::FORUM_COUNT)->create($data);

            $forums->each(function ($forum) use (&$category) {
                $this->createTopics($forum);

                $data = [
                    'parent_id' => $forum->id,
                    'category_id' => $category->id
                ];

                if ($forum->deleted_at) {
                    $data['deleted_at'] = $forum->deleted_at;
                }

                $children = factory(Forum::class, self::CHILD_COUNT)->create($data);

                $children->each(function ($child) {
                    $this->createTopics($child);
                });
            });

        });
    }

    private function createTopics($forum) {
        $topics = factory(Topic::class, self::TOPIC_COUNT)->create([
            'forum_id' => $forum->id
        ]);

        $topics->each(function ($topic) {
            $posts = factory(Post::class, self::POST_COUNT)->create([
                'topic_id' => $topic->id
            ]);
        });
    }
}
