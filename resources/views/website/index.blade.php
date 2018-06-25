@extends('layouts.website')

@section('content')
    <div class="card-body">
        <div class="directories">
            @foreach ($directories as $directory)
                <div class="directory">
                    <a href="{{ route('website.directories.show', ['slug' => $directory->slug]) }}">
                        <h2>{{ $directory->title }}</h2>
                        <p>Broj foruma: {{ $directory->boards()->count() }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@stop

@section('more-content')
    <section class="forum-info">
        <div class="card online-users">
            <div class="card-header">
                Ko je na mreži
            </div>
            <div class="card-body">
                <p>
                    Trenutno su {{ $people_online }} korisnika na mreži: <b>{{ $visible_online_count }}</b> vidljivih,
                    <b>{{ $invisible_online_count }}</b> sakrivenih i <b>{{ $guest_count }}</b> gosta
                    (ažurira se na svakih {{ $refresh_online_minutes}} minuta).
                </p>
                <div class="list">
                    @if ($visible_online->isEmpty())
                        <p>Nema aktivnih registrovanih korisnika.</p>
                    @else
                        <p>Registrovani korisnici:</p>
                        <ul>
                            @foreach ($visible_online as $user)
                                <li><a href="{{ route('website.users.show', ['profile' => $user->username]) }}">{{ $user->username }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="card statistics">
            <div class="card-header">
                Statistika
            </div>
            <div class="card-body">
                <p>
                    Ukupno poruka <b>{{ $post_count }}</b> &bull;
                    Ukupno tema <b>{{ $topic_count }}</b> &bull;
                    Ukupno članova <b>{{ $user_count }}</b> &bull;
                    Naš najnoviji član <a href="{{ route('website.users.show', ['profile' => $newest_user->username]) }}">{{ $newest_user->username }}</a>
                </p>
            </div>
        </div>
    </section>
@stop
