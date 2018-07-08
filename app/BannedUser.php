<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannedUser extends Model {
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'board_id', 'banned_by'
    ];
}
