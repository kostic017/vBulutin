<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    public $timestamps = false;

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function boardCategory()
    {
        return $this->belongsTo('App\BoardCategory');
    }
}
