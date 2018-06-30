<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model {
    use SoftDeletes;

    public $timestamps = false;

    public function is_read() {
        if (!Auth::check()) {
            return true;
        }

        $my_topics = $this->topics()->newerTopics()->get();
        $from_children = $this->topics_from_children()->newerTopics()->get();
        $all_topics = $my_topics->merge($from_children);

        foreach ($all_topics as $topic) {
            if ($topic->is_read() === false) {
                return false;
            }
        }

        return true;
    }

    public function last_post() {
        $all_topics = ($this->topics->merge($this->topics_from_children))->sortByDesc('updated_at');
        return $all_topics->count() ? $all_topics[0]->last_post() : null;
    }

    public function get_all_watchers() {
        $mine = Category::get_watchers('forum', $this->id);
        $fromCategory = Category::get_watchers('category', $this->category->id);
        $mine = $mine->merge($fromCategory);

        if ($parent = $this->parent) {
            $fromParent = Category::get_watchers('forum', $parent->id);
            $mine = $mine->merge($fromParent);
        }

        return $mine;
    }

    //region Relationships
    public function board() {
        return $this->category->board();
    }

    public function category() {
        return $this->belongsTo('App\Category');
    }

    public function parent() {
        return $this->belongsTo('App\Forum', 'parent_id');
    }

    public function children() {
        return $this->hasMany('App\Forum', 'parent_id');
    }

    public function topics() {
        return $this->hasMany('App\Topic');
    }

    public function topics_from_children() {
        return $this->hasManyThrough('App\Topic', 'App\Forum', 'parent_id');
    }

    public function posts() {
        return $this->hasManyThrough('App\Post', 'App\Topic');
    }
    //endregion
}
