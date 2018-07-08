<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'username', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'email_token', 'remember_token',
    ];

    public static function newest_user() {
        return self::select('username')->orderBy('registered_at', 'desc')->first();
    }

    public function routeNotificationForMail($notification) {
        return $this->email;
    }

    public function watched_categories() {
        return Category::findMany(UserWatches::select('category_id')->where('user_id', $this->id)->get()->toArray());
    }

    public function watched_forums() {
        return Forum::findMany(UserWatches::select('forum_id')->where('user_id', $this->id)->get()->toArray());
    }

    public function watched_topics()  {
        return Topic::findMany(UserWatches::select('topic_id')->where('user_id', $this->id)->get()->toArray());
    }

    public function is_banned_on($board_id) {
        return BannedUser::where('user_id', $this->id)
            ->where('board_id', $board_id)
            ->first() !== null;
    }

    public function can_ban_user_on($user) {
        if ($user->username === 'admin' || ($this->username !== 'admin' && $user->is_master)) return collect();
        return $this->owner_of->merge($this->admin_on()->where('owner_id', '<>', $user->id)->get());
    }

    //region Custom Attributes
    public function getPostCountAttribute() {
        return $this->posts()->count();
    }
    //endregion

    //region Scopes
    public function scopeActive($query) {
        return $query->whereNotIn('users.id',
            User::banned()->get(['id'])
                ->merge(User::banished()->get(['id']))
                ->toArray()
        );
    }

    public function scopeBanished($query) {
        return $query->where('users.is_banished', true);
    }

    public function scopeBanned($query) {
        return $query->whereIn('users.id', BannedUser::pluck('user_id'));
    }

    public function scopeSimpleUsers($query) {
        return $query->whereNotIn('users.id',
            User::simpleAdmins()->get(['id'])
                ->merge(User::forumOwners()->get(['id']))
                ->merge(User::masterAdmins()->get(['id']))
                ->toArray()
        );
    }

    public function scopeSimpleAdmins($query) {
        return $query->whereIn('users.id', BoardAdmin::pluck('user_id'));
    }

    public function scopeForumOwners($query) {
        return $query->whereIn('users.id', Board::pluck('owner_id'));
    }

    public function scopeMasterAdmins($query) {
        return $query->where('users.is_master', true);
    }
    //endregion

    //region Relationships
    public function posts()  {
        return $this->hasMany('App\Post');
    }

    public function banned_on() {
        return $this->belongsToMany('App\Board', 'banned_users')->using('App\BannedUser');
    }

    public function admin_on() {
        return $this->belongsToMany('App\Board', 'board_admins')->using('App\BoardAdmin');
    }

    public function owner_of() {
        return $this->hasMany('App\Board', 'owner_id');
    }
    //endregion
}
