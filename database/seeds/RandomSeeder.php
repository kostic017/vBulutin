<?php

use App\User;
use App\Post;
use App\Topic;
use App\Forum;
use App\Board;
use App\Profile;
use App\Category;
use App\Directory;
use App\UserModerates;

use Illuminate\Database\Seeder;

class RandomSeeder extends Seeder
{
    const USER_COUNT = 20;
    const DIRECTORY_COUNT = 2;
    const BOARDS_PER_DIRECTORY = 2;
    const CATEGORIES_PER_BOARD = 2;
    const ROOTS_PER_CATEGORY = 2;
    const CHILDREN_PER_ROOT = 2;
    const TOPICS_PER_FORUM = 2;
    const POSTS_PER_TOPIC = 2;
    const MAX_MODS_PER_CATEGORY = 2;

    // Ko moderise kategoriju, moderise i forume i potforume u njoj. Analogno,
    // ako neko moderise natforum, onda moderise i potforume. Dok npr. neko moze
    // da moderise samo odredjeni potforum. Posto ja sada hocu da ogranicim broj
    // kreiranih moderatora, vodim racuna o broju moderatora koje sam vec kreirao.
    // Polje je vezano za kategoriju kojoj kreiram moderature u datom trenutku!!!
    private $modCount;

    public function run()
    {
        factory(User::class, self::USER_COUNT)
            ->create()
            ->each(function ($user) {
                factory(Profile::class, 1)->create(['user_id' => $user->id]);
            });

        factory(Directory::class, self::DIRECTORY_COUNT)
            ->create()
            ->each(function ($directory) {
                factory(Board::class, self::BOARDS_PER_DIRECTORY)
                    ->create(['directory_id' => $directory->id])
                    ->each(function ($board) {
                        $owner = User::findOrFail($board->owned_by);
                        $owner->admin_of = $board->id;
                        $owner->save();

                        factory(Category::class, self::CATEGORIES_PER_BOARD)
                            ->create(['board_id' => $board->id])
                            ->each(function ($category) {
                                $this->modCount = 0;
                                $this->addModerators($category->id, 'category');
                                $this->createForums(null, $category->id);
                            });
                    });
            });
    }

    private function addModerators($sectionId, $sectionType) {
        $count = rand(0, self::MAX_MODS_PER_CATEGORY - $this->modCount);
        $this->modCount += $count;

        for ($i = 0; $i < $count; ++$i) {
            UserModerates::create([
                "{$sectionType}_id" => $sectionId,
                'user_id' => User::inRandomOrder()->first()->id,
            ]);
        }
    }

    private function createForums($parentId, $categoryId) {
        $forums = factory(Forum::class, self::CHILDREN_PER_ROOT)->create([
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
        $topics = factory(Topic::class, self::TOPICS_PER_FORUM)->create([
            'forum_id' => $forum->id
        ]);

        $topics->each(function ($topic) {
            $posts = factory(Post::class, self::POSTS_PER_TOPIC)->create([
                'topic_id' => $topic->id
            ]);
        });
    }
}
