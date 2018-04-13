<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    use Sluggable;
    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function section()
    {
        return $this->belongsTo('App\Section');
    }

    public function children()
    {
        return $this->hasMany('App\Forum', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Forum', 'parent_id');
    }

    public function topics()
    {
        return $this->hasMany('App\Topic');
    }

    public function watchers() {
        return $this->belongsToMany('App\User', 'forum_watchers');
    }

    public function sluggable()
    {
        return [
            'slug' => [ 'source' => 'title' ]
        ];
    }
}
