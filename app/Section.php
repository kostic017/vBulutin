<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use Sluggable;
    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function forums()
    {
        return $this->hasMany('App\Forum');
    }

    public function watchers()
    {
        return $this->belongsToMany('App\User', 'section_watchers');
    }

    public function sluggable()
    {
        return [
            'slug' => [ 'source' => 'title' ]
        ];
    }
}
