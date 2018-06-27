<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// U jednom redu se sme reci samo da li korisnik posmatra ili
// forum ili kategoriju ili temu; samo jedno polje sme biti
// postavljeno, a ostala dva moraju biti prazna. Ako posmatra
// kategoriju, posmatra i sve forume i teme u njoj; analogno
// za forume.

class UserWatches extends Model
{
    public $timestamps = false;
}
