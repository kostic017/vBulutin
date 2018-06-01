<?php

namespace App;

use Carbon\Carbon;

use App\Exceptions\UnexpectedException;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Forum extends Model
{
    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function postCount(): int
    {
        $count = 0;
        $topics = $this->topics()->get();
        foreach ($topics as $topic) {
            $count += $topic->posts()->get()->count();
        }
        return $count;
    }

    /**
     * Za svaku temu u forumu koja je azurirana u poslednjih
     * GC_READ_STATUS dana proverava da li je procitana. Vraca
     * 'new' ako ima neprocitanih tema, a 'old' ako su sve procitane.
     */
    public function readStatus(): string
    {
        $children = $this->children()->get();
        foreach ($children as $child) {
            if (!$this->readStatusHelper($this->readStatusTopics($child))) {
                return 'new';
            }
        }
        if (!$this->readStatusHelper($this->readStatusTopics($this))) {
            return 'new';
        }
        return 'old';
    }

    private function readStatusTopics(Forum $forum): Collection
    {
        $expDate = Carbon::now()->subDays((int)config('custom.gc.read_status'));
        return $forum->topics()->where('updated_at', '>', $expDate)->get();
    }

    private function readStatusHelper(Collection $topics): bool
    {
        foreach ($topics as $topic) {
            if ($topic->readStatus() === 'new') {
                return false;
            }
        }
        return true;
    }

    /**
     * Poredi poslednje poruke u svakoj temi u forumu i vraca najnoviju.
     */
    public function lastPost(): Post
    {
        $lastPost = null;
        $children = $this->children()->get();
        foreach ($children as $child) {
            $this->lastPostHelper($child, $lastPost);
        }
        $this->lastPostHelper($this, $lastPost);
        return $lastPost;
    }

    private function lastPostHelper(Forum $forum, ?Post &$lastPost): void
    {
        if ($lastTopic = $forum->topics()->orderBy('updated_at', 'desc')->first()) {
            $topicLastPost = $lastTopic->lastPost();
            if (!$lastPost) {
                $lastPost = $topicLastPost;
            } elseif ($lastPost->created_at->lt($topicLastPost->created_at)) {
                $lastPost = $topicLastPost;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo('App\Category');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo('App\Forum', 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany('App\Forum', 'parent_id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany('App\Topic');
    }

    public function watchers(): Collection
    {
        try {
            $mine = getWatchers('forum', $this->id);
            $fromCategory = getWatchers('category', $this->category()->firstOrFail()->id);
            $mine = $mine->merge($fromCategory);

            if ($parent = $this->parent()->first()) {
                $fromParent = getWatchers('forum', $parent->id);
                $mine = $mine->merge($fromParent);
            }

            return $mine;
        } catch (ModelNotFoundException $e) {
            throw new UnexpectedException($e);
        }
    }

    public function moderators(): Collection
    {
        try {
            $mine = getModerators('forum', $this->id);
            $fromCategory = getModerators('category', $this->category()->firstOrFail()->id);
            $mine = $mine->merge($fromCategory);

            if ($parent = $this->parent()->first()) {
                $fromParent = getModerators('forum', $parent->id);
                $mine = $mine->merge($fromParent);
            }

            return $mine;
        } catch (ModelNotFoundException $e) {
            throw new UnexpectedException($e);
        }
    }
}
