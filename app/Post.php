<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo('App\Topic');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function reports(): BelongsToMany
    {
        return $this->belongsToMany('App\UserReport', 'user_reports');
    }

    public function ratings(): BelongsToMany
    {
        return $this->belongsToMany('App\UserRating', 'user_ratings');
    }
}
