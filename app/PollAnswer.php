<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollAnswer extends Model
{
    protected $table = "poll_answers";

    public $timestamps = false;

    public function poll() {
        return $this->belongsTo("poll");
    }
}
