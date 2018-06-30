<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model {
    public $timestamps = false;

    //region Relationships
    public function boards() {
        return $this->hasMany('App\Board');
    }
    //endregion
}
