<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function forums(): HasMany
    {
        return $this->hasMany('App\Forum');
    }

    public function watchers(): Collection
    {
        return getWatchers('category', $this->id);
    }

    public function moderators(): Collection
    {
        return getModerators('category', $this->id);
    }
}
