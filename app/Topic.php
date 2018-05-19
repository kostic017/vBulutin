<?php

namespace App;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    public function lastPost() {
        return $this->posts()->orderBy('updated_at', 'desc')->first();
    }

    public function readStatus(): string
    {
        return Carbon::now()->diffInDays($this->updatedAt) >= (int)config('custom.gc.read_status') ||
            ReadTopics::where('topic_id', $this->id)->where('user_id', Auth::id())->get()->count() ?
            'old' : 'new';
    }

    public function poll()
    {
        return $this->hasOne('App\Poll');
    }

    public function solution()
    {
        return $this->hasOne('App\Post', 'solution_id');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function forum()
    {
        return $this->belongsTo('App\Forum');
    }


    public function watchers()
    {
        return User::findMany(UserWatches::select('user_id')->where('topic_id', $this->id)->get()->toArray());
    }

    public function readers()
    {
        return $this->belongsToMany('App\User', 'read_topics');
    }
}
