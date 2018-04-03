<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Topic extends Model
{
    use Sluggable;

    public function poll() {
        return $this->hasOne("App\Poll");
    }

    public function solution() {
        return $this->hasOne("App\Post", "solution_id");
    }

    public function posts() {
        return $this->hasMany("App\Post");
    }

    public function forum() {
        return $this->belongsTo("App\Forum");
    }

    public function watchers() {
        return $this->belongsToMany("App\User", "topic_watchers");
    }

    public function readers() {
        return $this->belongsToMany("App\User", "read_topics");
    }

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

}
