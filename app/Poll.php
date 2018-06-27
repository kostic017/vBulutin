<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    public $timestamps = false;

    //region Relationships
    public function topic()
    {
        return $this->belongsTo('App\Topic');
    }

    public function answers()
    {
        return $this->hasMany('App\PollAnswer');
    }
    //endregion
}
