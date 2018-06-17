<?php

use App\User;
use App\Directory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Default Admin
        $admin = new User;
        $admin->username = 'admin';
        $admin->password = 'admin';
        $admin->email = 'admin@forum.com';
        $admin->save();

        // Board Categories
        $categoryTitles = [
            "Igre", "Sport", "Nauka","Muzika",
            "Filmovi", "Društvo", "Računari", "Umetnost",
            "Priroda", "Životinje", "Automobili", "Drugo",
        ];
        foreach ($categoryTitles as $categoryTitle) {
            $category = new Directory;
            $category->title = $categoryTitle;
            $category->slug = str_slug($categoryTitle);
            $category->description = $categoryTitle . " i sve o tome...";
            $category->save();
        }
    }
}
