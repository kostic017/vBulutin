<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// U jednom redu se sme reci samo da li korisnik moderira ili
// forum ili kategoriju. Dakle, forum_id mora biti prazno ako
// je category_id postavljeno i obrnuto.

class UserModerates extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
