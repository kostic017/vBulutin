<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// U jednom redu se sme reci samo da li korisnik posmatra ili
// forum ili kategoriju ili temu; samo jedno polje sme biti
// posavljeno, a ostala dva moraju biti prazna.

class UserWatches extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
