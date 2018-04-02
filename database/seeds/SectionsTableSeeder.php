<?php

use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $f = factory(App\Section::class, 3);
        $f = $f->create();
        $f = $f->each(function ($section) {

            $f = factory(App\Forum::class, 4);
            $f = $f->create(["section_id" => $section->id]);
            $f = $f->each(function ($forum) use (&$section) {
                $f = factory(App\Forum::class, 2);
                $f = $f->create([ "section_id" => $section->id, "parent_id" => $forum->id ]);
                $f = $f->each(function ($child) use (&$forum) {
                    $forum->children()->save($child);
                });

                $section->forums()->save($forum);

            });

        });
    }
}
