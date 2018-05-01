@extends('layouts.public')

@section('content')
    <div class="bgc-main">
        <h2>{{ $user->username }}</h2>
        <div>@avatar(big)</div>
        <div class="info">
            <p>
                <b>Pridružio</b><br>
                {{ extractDate($user->registered_at) }}
            </p>
            <p>
                <b>Ukupno poruka</b><br>
                {{ $user->posts()->get()->count() }}
            </p>
            <p>
                <a href="">Sve poruke korisnika {{ $user->username }}</a><br>
                <a href="">Sve teme koje je započeo korisnik {{ $user->username }}</a><br>
                <a href="">Sve teme u kojima je učestvovao korisnik {{ $user->username }}</a>
            </p>
        </div>
    </div>
@stop
