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
        return User::findMany(UserWatches::select('user_id')->where('category_id', $this->id)->get()->toArray());
    }

    public function moderators()
    {
        return User::findMany(UserModerates::select('user_id')->where('category_id', $this->id)->get()->toArray());
    }
}
