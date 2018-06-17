<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    public $timestamps = false;

    public function boards()
    {
        return $this->hasMany('App\Board');
    }
}
