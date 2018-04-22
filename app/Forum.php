<?php

namespace App;

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
        $topics = $this->topics();
        foreach ($topics as $topic) {
            $count += $topic->posts()->count();
        }
        return $count;
    }

    /**
     * Za svaku temu u forumu proverava da li je procitana.
     * Ako bar jedna tema nije procitana onda forum nije procitan.
     * Ako je forum procitan vraca 'old', ako nije vraca 'new'.
     */
    public function readStatus(): string
    {
        $children = $this->children();

        foreach ($children as $child) {
            $topics = $child->topics();
            if (!$this->readStatusHelper($topics)) {
                return 'new';
            }
        }

        $topics = $this->topics();
        if (!$this->readStatusHelper($topics)) {
            return 'new';
        }

        return 'old';
    }

    private function readStatusHelper($topics): bool
    {
        foreach ($topics as $topic) {
            if (!$topic->readStatus()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Poredi poslednje poruke u svakoj temi u forumu i vraca najnoviju.
     */
    public function lastPost()
    {
        $lastPost = null;
        $children = $this->children();

        foreach ($children as $child) {
            $topics = $child->topics();
            $this->lastPostHelper($topics, $lastPost);
        }

        $topics = $this->topics();
        $this->lastPostHelper($topics, $lastPost);

        return $lastPost;
    }

    private function lastPostHelper($topics, &$lastPost)
    {
        foreach ($topics as $topic) {
            $topicLastPost = $topic->lastPost();
            if ($lastPost) {
                $lastPost = ($lastPost->updated_at->lt($topicLastPost)) ? $topicLastPost : $lastPost;
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
