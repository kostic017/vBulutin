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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Topic extends Model
{

    public function lastPost(): Post
    {
        return $this->posts()->orderBy('updated_at', 'desc')->first();
    }

    public function readStatus(): string
    {
        return Carbon::now()->diffInDays($this->updatedAt) >= (int)config('custom.gc.read_status') ||
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

    public function solution(): HasOne
    {
        return $this->hasOne('App\Post', 'solution_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany('App\Post');
    }

    public function readers()
    {
        return $this->belongsToMany('App\User', 'read_topics');
    }

    public function watchers(): HasMany
    {
        try {
            // Ne mora da mergujemo sa posmatracima kategorije
            // posto to vec radimo kad trazimo posmatrace foruma.
            $forum = $this->forum()->firstOrFail();
            $mine = getWatchers('topic', $this->id);
            return $mine->merge($forum->getWatchers());
        } catch (ModelNotFoundException $e) {
            throw new UnexpectedException($e);
        }
    }
}
