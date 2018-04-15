@extends('layouts.admin')

@section('content')
    <h2>{{ __('info.admin_panel') }}</h2>
    <p>
        Novokreiranim forumima i kategorijama se automatski dodeljuje poslednja pozicija u tabeli.
        Mogu se repozicionirati samo preko stranice 'Pozicioniranje'.
    </p>
    <p>
        Ništa se ne može u potpunosti obrisati već odlazi u kantu za smeće, iz koje se može vratiti
        u bilo kom trenutku. Ono što je obrisano vidljivo je samo preko ovog panela, a od svih koji
        mu ne mogu pristupiti je sakriveno. Ako se obriše kategorija, automatski se brišu i svi forumi
        koji pripadaju toj kategoriji. Nakon vraćanja kategorije, forume je neophodno ručno vratiti.
    </p>
    <p>
        Jedini način da se forum prebaci u drugu kategoriju je preko stranice 'Pozicioniranje'.
        Ukoliko se premesti u obrisanu kategoriju, onda i on postaje obrisan. Analogno važi i za
        potforume.
    </p>
@stop
