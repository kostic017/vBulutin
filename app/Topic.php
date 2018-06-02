<?php

namespace App;

use Auth;
use Carbon\Carbon;
use App\Exceptions\UnexpectedException;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Topic extends Model
{

    public function solution(): ?Post
    {
        return Post::find($this->solution_id);
    }

    public function lastPost(): Post
    {
        return $this->posts()->orderBy('created_at', 'desc')->firstOrFail();
    }

    public function firstPost(): Post
    {
        return $this->posts()->orderBy('created_at', 'asc')->firstOrFail();
    }

    public function starter(): User
    {
        return $this->firstPost()->user()->firstOrFail();
    }

    public function readStatus(): string
    {
        return Carbon::now()->diffInDays($this->updatedAt) >= (int)config('custom.gc_read_status_days') ||
            ReadTopics::where('topic_id', $this->id)->where('user_id', Auth::id())->get()->count() ?
                'old' : 'new';
    }

    public function forum(): BelongsTo
    {
        return $this->belongsTo('App\Forum');
    }

    public function poll(): HasOne
    {
        return $this->hasOne('App\Poll');
    }

    public function posts(): HasMany
    {
        return $this->hasMany('App\Post');
    }

    public function readers(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'read_topics');
    }

    public function watchers(): HasMany
    {
        // Ne mora da mergujemo sa posmatracima kategorije
        // posto to vec radimo kad trazimo posmatrace foruma.

        $forum = $this->forum()->firstOrFail();
        $mine = getWatchers('topic', $this->id);
        return $mine->merge($forum->getWatchers());
    }
}
