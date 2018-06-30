<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    use SoftDeletes;

    public $timestamps = false;

    public function get_all_watchers() {
        return self::get_watchers('category', $this->id);
    }

    /**
    * @param string $my_table 'categories'|'forums'|'topics'
    * @param string $my_id    ID of the specific resource.
    *
    * @return Illuminate\Database\Eloquent\Collection
    */
    public static function get_watchers($my_table, $my_id) {
        return User::findMany(UserWatches::select('user_id')->where("{$my_table}_id", $my_id)->get()->toArray());
    }

    //region Relationships
    public function board() {
        return $this->belongsTo('App\Board');
    }

    public function forums() {
        return $this->hasMany('App\Forum');
    }

    public function parent_forums() {
        return $this->forums()->whereNull('parent_id');
    }
    //endregion
}
