<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
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
        return $this->belongsToMany('App\User', 'category_watchers');
    }
}
