@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/board-public.css') }}">
    @yield('styles')
@overwrite

@section('content')
    <section class="content">
       @yield('content')
    </section>

    <section class="forum-info">
        <div class="card online-users">
            <div class="card-header">
                Ko je na mreži
            </div>
            <div class="card-body">
                <p>
                    Trenutno su {{ $peopleOnline }} korisnika na mreži: <b>{{ $visibleOnlineUsersCount }}</b> vidljivih,
                    <b>{{ $invisibleOnlineUsersCount }}</b> sakrivenih i <b>{{ $guestCount }}</b> gosta
                    (ažurira se na svakih {{ $onlineUsersMinutes}} minuta).
                </p>
                <div class="list">
                    @if ($visibleOnlineUsers->isEmpty())
                        <p>Nema aktivnih registrovanih korisnika.</p>
                    @else
                        <p>Registrovani korisnici:</p>
                        <ul>
                            @foreach ($visibleOnlineUsers as $user)
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
                    Ukupno poruka <b>{{ $postCount }}</b> &bull;
                    Ukupno tema <b>{{ $topicCount }}</b> &bull;
                    Ukupno članova <b>{{ $userCount }}</b> &bull;
                    Naš najnoviji član <a href="{{ route('website.users.show', ['profile' => $newestUser->username]) }}">{{ $newestUser->username }}</a>
                </p>
        </div>
    </section>
@overwrite
