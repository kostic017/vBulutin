<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public static function newestUser(): User
    {
        return self::select('username')->orderBy('registered_at', 'desc')->first();
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'email_token', 'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function reports()
    {
        return $this->belongsToMany('App\Post', 'user_reports');
    }

    public function ratings()
    {
        return $this->belongsToMany('App\Post', 'user_ratings');
    }

    public function readTopics()
    {
        return $this->belongsToMany('App\Topic', 'read_topics');
    }

    public function pollAnswers()
    {
        return $this->belongsToMany('App\PollAnswer', 'user_answers');
    }

    public function watchedCategories()
    {
        return $this->belongsToMany('App\Category', 'category_watchers');
    }

    public function watchedForums()
    {
        return $this->belongsToMany('App\Forum', 'forum_watchers');
    }

    public function watchedTopics()
    {
        return $this->belongsToMany('App\Topic', 'topic_watchers');
    }
}
