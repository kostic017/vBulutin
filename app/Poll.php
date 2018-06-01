<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poll extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function topic(): BelongsTo
    {
        return $this->belongsTo('App\Topic');
    }

    public function answers(): HasMany
    {
        return $this->hasMany('App\PollAnswer');
    }
}
