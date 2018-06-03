@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    @yield('styles')
@overwrite

@section('nav-auth')
    @if (is_admin())
        <li><a class="nav-link" href="{{ route('back.index') }}">Admin panel</a></li>
    @endif
    @yield('nav-auth')
@overwrite

@section('scripts')
    @yield('scripts')
@overwrite

@section('content')
        {{--
        <form name="search" action="search.php">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Pretraga..." aria-label="Pretraga">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary submitsearch" type="button"><i class="fas fa-search"></i></button>
                    <button class="btn btn-outline-secondary advancedsearch" type="button"><i class="fas fa-cog"></i></button>
                </div>
            </div>
        </form>
        --}}

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
                                    <li><a href="{{ route('front.users.show', ['profile' => $user->username]) }}">{{ $user->username }}</a></li>
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
                        Naš najnoviji član <a href="{{ route('front.users.show', ['profile' => $newestUser->username]) }}">{{ $newestUser->username }}</a>
                    </p>
            </div>
        </section>

        <section class="footer">
            <div class="copyright">
               Copyright &copy; {{ date('Y') }} Nikola Kostić
            </div>
        </section>
@overwrite
