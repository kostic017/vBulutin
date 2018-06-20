<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    public function board()
    {
        return $this->belongsTo('App\Board');
    }

    public function forums()
    {
        return $this->hasMany('App\Forum');
    }

    public function watchers()
    {
        return getWatchers('category', $this->id);
    }
}
