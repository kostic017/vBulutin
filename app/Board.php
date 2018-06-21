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

    public function admins()
    {
        return $this->hasMany('App\User');
    }

    public function board()
    {
        return $this->belongsTo('App\Board');
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function directory()
    {
        return $this->belongsTo('App\Directory');
    }

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'owner_id');
    }
}
