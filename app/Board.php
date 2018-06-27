<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    public $timestamps = false;

    public function is_admin()
    {
        return Auth::check() && ($this->is_owner() || $this->admins()->where('user_id', Auth::id())->first());
    }

    public function is_owner()
    {
        return Auth::check() && $this->owner_id === Auth::id();
    }

    //region Relationships
    public function directory()
    {
        return $this->belongsTo('App\Directory');
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function forums()
    {
        return $this->hasManyThrough('App\Forum', 'App\Category');
    }

    public function topics()
    {
        return Topic::select('topics.*')
            ->join('forums', 'topics.forum_id', 'forums.id')
            ->join('categories', 'forums.category_id', 'categories.id')
            ->join('boards', 'categories.board_id', 'boards.id')
            ->where('boards.url', $this->url);
    }

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'owner_id');
    }

    public function admins()
    {
        return $this->hasMany('App\BoardAdmin');
    }
    //endregion
}
