<?php

use App\User;
use App\Profile;
use App\Directory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $admin = new User;
        $admin->username = 'admin';
        $admin->password = \Hash::make('admin');
        $admin->email = 'admin@forum.com';
        $admin->is_master = true;
        $admin->save();
        factory(Profile::class, 1)->create(['user_id' => $admin->id]);

        $directories = [
            "Igre", "Sport", "Nauka","Muzika",
            "Filmovi", "Društvo", "Računari", "Umetnost",
            "Priroda", "Životinje", "Automobili", "Drugo",
        ];
        foreach ($directories as $directory) {
            $category = new Directory;
            $category->title = $directory;
            $category->slug = str_slug($directory);
            $category->description = $directory . " i sve o tome...";
            $category->save();
        }
    }
}
