<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Forum extends Model
{
    use Sluggable;

    public $timestamps = false;

    public function section() {
        return $this->belongsTo("App\Section");
    }

    public function children() {
        return $this->hasMany("App\Forum", "parent_id");
    }

    public function parent() {
        return $this->belongsTo("App\Forum", "parent_id");
    }

    public function topics() {
        return $this->hasMany("App\Topic");
    }

    public function watchers() {
        return $this->belongsToMany("App\User", "forum_watchers");
    }

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
