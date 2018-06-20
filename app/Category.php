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
        return self::get_watchers('category', $this->id);
    }

    public static function get_watchers($myTable, $myId)
    {
        return User::findMany(UserWatches::select('user_id')->where("{$myTable}_id", $myId)->get()->toArray());
    }
}
