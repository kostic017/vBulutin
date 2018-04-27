<?php

namespace App;

use Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    use Sluggable;
    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function postCount()
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

    private function lastPostHelper(Forum $forum, ?Post &$lastPost)
    {
        if ($lastTopic = $forum->topics()->orderBy('updated_at', 'desc')->first()) {
            $topicLastPost = $lastTopic->lastPost();
            if ($lastPost) {
                $lastPost = ($lastPost->updated_at->lt($topicLastPost->updated_at)) ? $topicLastPost : $lastPost;
            } else {
                $lastPost = $topicLastPost;
            }
        }
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function children()
    {
        return $this->hasMany('App\Forum', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Forum', 'parent_id');
    }

    public function topics()
    {
        return $this->hasMany('App\Topic');
    }

    public function watchers() {
        return $this->belongsToMany('App\User', 'forum_watchers');
    }

    public function sluggable()
    {
        return [
            'slug' => [ 'source' => 'title' ]
        ];
    }
}
