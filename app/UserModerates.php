<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// U jednom redu se sme reci samo da li korisnik moderira ili
// forum ili kategoriju. Dakle, forum_id mora biti prazno ako
// je category_id postavljeno i obrnuto. Korisnik koji moderira
// neku kategoriju, modrerira i sve forume u njoj.

class UserModerates extends Model
{
    public $timestamps = false;
}
