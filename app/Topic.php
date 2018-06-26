<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;

    public function solution()
    {
        return Post::find($this->solution_id);
    }

    public function postCount()
    {
        return $this->posts()->count();
    }

    public function lastPost()
    {
        return $this->posts()->orderBy('created_at', 'desc')->firstOrFail();
    }

    public function firstPost()
    {
        return $this->posts()->orderBy('created_at', 'asc')->firstOrFail();
    }

    public function starter()
    {
        return $this->firstPost()->user()->firstOrFail();
    }

    public function readStatus()
    {
        return \Carbon::now()->diffInDays($this->updatedAt) >= (int)config('custom.gc_read_status_days') ||
            ReadTopics::where('topic_id', $this->id)->where('user_id', \Auth::id())->get()->count() ?
                'old' : 'new';
    }

    public function poll()
    {
        return $this->hasOne('App\Poll');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function readers()
    {
        return $this->belongsToMany('App\User', 'read_topics');
    }

    public function watchers()
    {
        // Ne mora da mergujemo sa posmatracima kategorije
        // posto to vec radimo kad trazimo posmatrace foruma.

        $forum = $this->forum()->firstOrFail();
        $mine = getWatchers('topic', $this->id);
        return $mine->merge($forum->getWatchers());
    }

    public function forum()
    {
        return $this->belongsTo('App\Forum');
    }

    public function category()
    {
        return $this->forum()->firstOrFail()->category();
    }

    public function board()
    {
        return $this->category()->firstOrFail()->board();
    }
}
