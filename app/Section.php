<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Section extends Model
{
    use Sluggable;

    public $timestamps = false;

    public function forums() {
        return $this->hasMany("App\Forum");
    }

    public function watchers() {
        return $this->belongsToMany("App\User", "section_watchers");
    }

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
