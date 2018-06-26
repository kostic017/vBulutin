<?php

use App\User;
use App\Post;
use App\Topic;
use App\Forum;
use App\Board;
use App\Profile;
use App\Category;
use App\Directory;

use Illuminate\Database\Seeder;

class RandomSeeder extends Seeder
{
    const USER_COUNT = 20;
    const MIN_RAND_FOR_CHILDREN = 90;

    const BOARDS_PER_DIRECTORY = 1;
    const CATEGORIES_PER_BOARD = 1;
    const ROOTS_PER_CATEGORY = 1;
    const CHILDREN_PER_ROOT = 1;
    const TOPICS_PER_FORUM = 1;
    const POSTS_PER_TOPIC = 1;

    public function run()
    {
        factory(User::class, self::USER_COUNT)
            ->create()
            ->each(function ($user) {
                factory(Profile::class, 1)->create(['user_id' => $user->id]);
            });

        Directory::all()->each(function ($directory) {
            factory(Board::class, self::BOARDS_PER_DIRECTORY)
                ->create(['directory_id' => $directory->id])
                ->each(function ($board) {
                    factory(Category::class, self::CATEGORIES_PER_BOARD)
                        ->create(['board_id' => $board->id])
                        ->each(function ($category) {
                            $this->createForums(null, $category->id);
                        });
                });
        });
    }

    private function createForums($parent_id, $category_id) {
        factory(Forum::class, self::CHILDREN_PER_ROOT)
            ->create([
                'parent_id' => $parent_id,
                'category_id' => $category_id
            ])
            ->each(function ($forum) {
                $this->createTopics($forum);
                if ($forum->parent_id === null && rand(0, 100) > self::MIN_RAND_FOR_CHILDREN) {
                    $this->createForums($forum->id, $forum->category_id);
                }
            });
    }

    private function createTopics($forum) {
        factory(Topic::class, self::TOPICS_PER_FORUM)
            ->create([
                'forum_id' => $forum->id
            ])
            ->each(function ($topic) {
                $posts = factory(Post::class, self::POSTS_PER_TOPIC)->create([
                    'topic_id' => $topic->id
                ]);
            });
    }
}
