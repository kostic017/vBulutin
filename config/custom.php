<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Koristi ID u URL
    |--------------------------------------------------------------------------
    |
    | Svaka sekcija, forum, korisnik (...) se moze identifikovati pomocu
    |   1) jedinstvenog celobrojnog ID-a
    |   2) jedinstvenog naslova/korisnickog imena
    |
    | Kada su u pitanju naslovi, za identifikaciju stranica se koristi
    | jedinstvena, skracena, URL-friendly verzija naslova zvana 'slug'.
    |
    | Po defaultu, koristi se druga opcija jer se smatra da
    | pruzaju bolje korisnicko iskustvo, i iz slicnih razloga.
    |
    | Primer:
    | use_id = true   http://forum41.com/forums/1
    | use_id = false  http://forum41.com/forums/test-forum
    |
    */

    'url_id' => env('URL_ID', false)
];
