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

    public function last_post()
    {
        return $this->posts()->orderBy('created_at', 'desc')->firstOrFail();
    }

    public function first_post()
    {
        return $this->posts()->orderBy('created_at', 'asc')->firstOrFail();
    }

    public function starter()
    {
        return $this->first_post()->user;
    }

    public function is_read()
    {
        if (!\Auth::check())
            return true;
        if (\Carbon::now()->diffInDays($this->updatedAt) >= (int)config('custom.gc_read_status_days'))
            return true;
        if ($this->readers()->where('user_id', \Auth::id())->count())
            return true;
        return false;
    }

    public function get_all_watchers()
    {
        // Ne mora da mergujemo sa posmatracima kategorije
        // posto to vec radimo kad trazimo posmatrace foruma.

        $forum = $this->firstOrFail();
        $mine = Category::get_watchers('topic', $this->id);
        return $mine->merge($forum->get_all_watchers());
    }

    public function scopeNewerTopics($query)
    {
        return $query->where('updated_at', '>', \Carbon::now()->subDays((int)config('custom.gc_read_status_days')));
    }

    //region Relationships
    public function board()
    {
        return $this->category->board();
    }

    public function category()
    {
        return $this->forum->category();
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function forum()
    {
        return $this->belongsTo('App\Forum');
    }

    public function poll()
    {
        return $this->hasOne('App\Poll');
    }

    public function readers()
    {
        return $this->belongsToMany('App\User', 'read_topics');
    }
    //endregion
}
