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
    const DIRECTORY_COUNT = 2;
    const BOARDS_PER_DIRECTORY = 2;
    const CATEGORIES_PER_BOARD = 2;
    const ROOTS_PER_CATEGORY = 2;
    const CHILDREN_PER_ROOT = 2;
    const TOPICS_PER_FORUM = 2;
    const POSTS_PER_TOPIC = 2;

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
                                $this->createForums(null, $category->id);
                            });
                    });
            });
    }

    private function createForums($parentId, $categoryId) {
        $forums = factory(Forum::class, self::CHILDREN_PER_ROOT)->create([
            'parent_id' => $parentId,
            'category_id' => $categoryId
        ]);

        $forums->each(function ($forum) use ($parentId, $categoryId) {
            $this->createTopics($forum);

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
