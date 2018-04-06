<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function topic()
    {
        return $this->belongsTo('App\Topic');
    }

    public function answers()
    {
        return $this->hasMany('App\PollAnswer');
    }
}
