<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $dates = [
        'created_at'
    ];

    public function topic()
    {
        return $this->belongsTo('App\Topic');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function reports()
    {
        return $this->belongsToMany('App\UserReport', 'user_reports');
    }

    public function ratings()
    {
        return $this->belongsToMany('App\UserRating', 'user_ratings');
    }
}
