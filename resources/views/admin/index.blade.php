@extends('layouts.admin')

@section('content')
    <div class="card p-3">

        <div class="card-header">
            <h2>{{ __('info.admin_panel') }}</h2>
        </div>

        <div class="card-body">
            <p>
                Novokreiranim forumima i kategorijama se automatski dodeljuje poslednja pozicija u tabeli.
                Mogu se repozicionirati samo preko stranice 'Pozicioniranje'.
            </p>
            <p>
                Ništa se ne može u potpunosti obrisati već odlazi u kantu za smeće, iz koje se može vratiti
                u bilo kom trenutku. Ono što je obrisano vidljivo je samo preko ovog panela. Ako se obriše
                kategorija, automatski se brišu i svi forumi koji pripadaju toj kategoriji. Nakon vraćanja
                kategorije, forume je neophodno ručno vratiti. Analogno važi i za brisanje natforuma.
            </p>
            <p>
                Jedini način da se forum prebaci u drugu kategoriju je preko stranice 'Pozicioniranje'.
                Ukoliko se premesti u obrisanu kategoriju, onda i on postaje obrisan. Analogno važi i za
                potforume.
            </p>
        </div>

    </div>
@stop
