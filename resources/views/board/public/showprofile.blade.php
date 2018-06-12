@extends('layouts.public')

@section('content')
    <div class="show-profile bgc-main p-main">
        <h2 class="username">{{ $user->username }}</h2>
        <section>
            <div class="avatar">
                @avatar(big)
            </div>
            {{-- <p>
                <a href="">Sve poruke korisnika {{ $user->username }}</a><br>
                <a href="">Sve teme koje je započeo korisnik {{ $user->username }}</a><br>
                <a href="">Sve teme u kojima je učestvovao korisnik {{ $user->username }}</a>
            </p> --}}
            <dl>
                <dt>Pridružio</dt>
                <dd>{{ extractDate($user->registered_at) }}</dd>
                <dt>Ukupno poruka</b><dt>
                <dd>{{ $user->posts()->get()->count() }}</dd>
                <dt>Pol</dt>
                <dd>{{ $profile->sex ?? '-' }}</dd>
            </dl>
            <dl>
                <dt>Datum rođenja</dt>
                <dd>{{ $profile->birthday_on ?? '-' }}</dd>
                <dt>Mesto rođenja</dt>
                <dd>{{ $profile->birthplace ?? '-' }}</dd>
                <dt>Prebivalište</dt>
                <dd>{{ $profile->residence ?? '-' }}</dd>
                <dt>Zanimanje</dt>
                <dd>{{ $profile->job ?? '-' }}</dd>
            </dl>
            <dl>
                <dt>O meni</dt>
                <dd>{{ $profile->about ?? '-' }}</dd>
                <dt>Potpis</dt>
                <dd>{{ $profile->signature ?? '-' }}</dd>
            </dl>
        </section>
        @if ($is_admin || $user->id == Auth::id())
            <a class="btn btn-success" href="{{ route('website.users.edit', ['profile' => $user->username]) }}">Izmeni profil</a>
        @endif
    </div>
@stop
