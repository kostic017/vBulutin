<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Profile extends Model {
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    //region Relationships
    public function user() {
        return $this->belongsTo('App\User');
    }
    //endregion
}
