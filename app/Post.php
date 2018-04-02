<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function topic() {
        return $this->belongsTo("App\Topic");
    }

    public function solution() {
        return $this->hasOne("App\Topics", "solution_id");
    }

    public function reports() {
        return $this->belongsToMany("App\UserReport", "user_reports");
    }

    public function ratings() {
        return $this->belongsToMany("App\UserRating", "user_ratings");
    }
}
