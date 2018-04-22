<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Topic extends Model
{
    use Sluggable;

    public function lastPost() {
        return $this->posts()->orderBy('updated_at', 'desc')->first();
    }

    public function readStatus(): boolean
    {
        if (!Auth::check()) return false; // za sada, posle i za goste da se napravi
        return Carbon::now()->diffInDays($this->updatedAt) >= (int)config('custom.gc.read_status')
            || ReadTopics::where('topic_id', $this->id)->where('user_id', Auth::id())->count();
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
        return $this->belongsToMany('App\User', 'topic_watchers');
    }

    public function readers()
    {
        return $this->belongsToMany('App\User', 'read_topics');
    }

    public function sluggable()
    {
        return [
            'slug' => [ 'source' => 'title' ]
        ];
    }

}
