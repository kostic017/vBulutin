<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadTopic extends Model
{
    public $timestamps = false;
    public $fillable = [
        'user_id', 'topic_id'
    ];
}
