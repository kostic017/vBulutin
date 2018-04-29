@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    @yield('styles')
@overwrite

@section('scripts')
    @yield('scripts')
@overwrite

@section('navigation')
@stop

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
                    <p>Trenutno su 2 korisnika na mreži: <b>0</b> registrovanih, <b>0</b> sakrivenih i <b>2</b> gosta (ažurira se na svakih 5 minuta).</p>
                    <p>Na mreži je najviše bilo <b>233</b> korisnika 24. aprila 2017.</p>
                    <div class="list">
                        <p>Registrovani korisnici:</p>
                        <ul>
                            <li><a href="">Kostić</a></li>
                            <li><a href="">Kostić</a></li>
                        </ul>
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
                        Naš najnoviji član <a href="{{ route('public.user', ['user' => $newestUser->username]) }}">{{ $newestUser->username }}</a>
                    </p>
            </div>
        </section>
@overwrite
