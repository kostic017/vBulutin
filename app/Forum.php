<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    use SoftDeletes;

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
     * GC_READ_STATUS_DAYS dana proverava da li je procitana. Vraca
     * 'new' ako ima neprocitanih tema, a 'old' ako su sve procitane.
     */
    public function readStatus()
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

    private function readStatusTopics($forum)
    {
        $expDate = Carbon::now()->subDays((int)config('custom.gc_read_status_days'));
        return $forum->topics()->where('updated_at', '>', $expDate)->get();
    }

    private function readStatusHelper($topics)
    {
        foreach ($topics as $topic) {
            if ($topic->readStatus() === 'new') {
                return false;
            }
        }
        return true;
    }

    public function lastPost()
    {
        $lastPost = null;
        $children = $this->children()->get();
        foreach ($children as $child) {
            $this->lastPostHelper($child, $lastPost);
        }
        $this->lastPostHelper($this, $lastPost);
        return $lastPost;
    }

    private function lastPostHelper($forum, &$lastPost)
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

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function parent()
    {
        return $this->belongsTo('App\Forum', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Forum', 'parent_id');
    }

    public function topics()
    {
        return $this->hasMany('App\Topic');
    }

    public function watchers()
    {
        $mine = getWatchers('forum', $this->id);
        $fromCategory = getWatchers('category', $this->category()->firstOrFail()->id);
        $mine = $mine->merge($fromCategory);

        if ($parent = $this->parent()->first()) {
            $fromParent = getWatchers('forum', $parent->id);
            $mine = $mine->merge($fromParent);
        }

        return $mine;
    }

    public function moderators()
    {
        $mine = getModerators('forum', $this->id);
        $fromCategory = getModerators('category', $this->category()->firstOrFail()->id);
        $mine = $mine->merge($fromCategory);

        if ($parent = $this->parent()->first()) {
            $fromParent = getModerators('forum', $parent->id);
            $mine = $mine->merge($fromParent);
        }

        return $mine;
    }
}
