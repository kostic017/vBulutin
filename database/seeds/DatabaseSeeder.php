<?php

use App\User;
use App\Post;
use App\Topic;
use App\Forum;
use App\Profile;
use App\Category;
use App\UserModerates;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    const FORUM_COUNT = 4;
    const CHILD_COUNT = 2;
    const TOPIC_COUNT = 5;
    const POST_COUNT = 10;
    const USER_COUNT = 30;
    const CATEGORY_COUNT = 3;
    const MAX_MODS_PER_CAT_COUNT = 10;

    // Ko moderise kategoriju, moderise i forume i potforume u njoj. Analogno,
    // ako neko moderise natforum, onda moderise i potforume. Dok npr. neko moze
    // da moderise samo odredjeni potforum. Posto ja sada hocu da ogranicim broj
    // kreiranih moderatora, vodim racuna o broju moderatora koje sam vec kreirao.
    // Polje je vezano za kategoriju kojoj kreiram moderature u datom trenutku!!!
    private $modCount;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createUsers();
        factory(Category::class, self::CATEGORY_COUNT)
            ->create()
            ->each(function ($category) {
                $this->modCount = 0;
                $this->addModerators($category->id, 'category');
                $this->createForums(null, $category->id);
            });
    }

    private function createUsers() {
        $users = factory(User::class, self::USER_COUNT - 1)->create();

        $users[] = factory(User::class, 1)->create([
            'username' => 'zoki',
            'email_token' => null,
            'is_confirmed' => true,
            'is_admin' => true,
        ])->first();

        $users->each(function ($user) {
            factory(Profile::class, 1)->create(['user_id' => $user->id]);
        });
    }

    private function addModerators($sectionId, $sectionType) {
        $count = rand(0, self::MAX_MODS_PER_CAT_COUNT - $this->modCount);
        $this->modCount += $count;

        for ($i = 0; $i < $count; ++$i) {
            UserModerates::create([
                "{$sectionType}_id" => $sectionId,
                'user_id' => User::inRandomOrder()->first()->id,
            ]);
        }
    }

    private function createForums($parentId, $categoryId) {
        $forums = factory(Forum::class, self::CHILD_COUNT)->create([
            'parent_id' => $parentId,
            'category_id' => $categoryId
        ]);

        $forums->each(function ($forum) use ($parentId, $categoryId) {
            $this->createTopics($forum);
            $this->addModerators($forum->id, 'forum');

            if ($parentId === null) {
                $this->createForums($forum->id, $categoryId);
            }
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
