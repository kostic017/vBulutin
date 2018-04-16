<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use Sluggable;
    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'position'
    ];

    public function forums()
    {
        return $this->hasMany('App\Forum');
    }

    public function watchers()
    {
        return $this->belongsToMany('App\User', 'category_watchers');
    }

    public function sluggable()
    {
        return [
            'slug' => [ 'source' => 'title' ]
        ];
    }
}
