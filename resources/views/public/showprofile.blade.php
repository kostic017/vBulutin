@extends('layouts.public')

@section('content')
    <div class="edit-profile bgc-main p-main">
        <h2 class="username">{{ $user->username }}</h2>

        <div class="avatar">
            @avatar(big)
        </div>

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

        @if ($user->id == Auth::id() || Auth::user()->is_admin)
            <a class="btn btn-success" href="{{ route('public.profile.edit', ['profile' => $user->username]) }}">Izmeni profil</a>
        @endif

    </div>
@stop
