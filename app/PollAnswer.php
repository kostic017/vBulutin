<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollAnswer extends Model
{
    public $timestamps = false;

    //region Relationships
    public function poll()
    {
        return $this->belongsTo('poll');
    }
    //endregion
}
