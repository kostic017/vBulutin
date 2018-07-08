@extends('layouts.website')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="show-profile bgc-main p-main">
                <h2 class="username">{{ $user->username }}</h2>
                @if ($user->is_banished)
                    <p class="text-danger"><strong>Korisnik je prognan.</strong></p>
                @endif
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
                        <dd>{{ extract_date($user->registered_at) }}</dd>
                        <dt>Ukupno poruka</b><dt>
                        <dd>{{ $user->posts()->get()->count() }}</dd>
                        <dt>Pol</dt>
                        <dd><?php
                            switch ($user->sex) {
                                case 'm': echo 'Muški'; break;
                                case 'f': echo 'Ženski'; break;
                                case 'o': echo 'Drugo'; break;
                                default: echo '-'; break;
                            }
                        ?></dd>
                    </dl>
                    <dl>
                        <dt>Datum rođenja</dt>
                        <dd>{{ $user->birthday_on ?: '-' }}</dd>
                        <dt>Mesto rođenja</dt>
                        <dd>{{ $user->birthplace ?: '-' }}</dd>
                        <dt>Prebivalište</dt>
                        <dd>{{ $user->residence ?: '-' }}</dd>
                        <dt>Zanimanje</dt>
                        <dd>{{ $user->job ?: '-' }}</dd>
                    </dl>
                    <dl>
                        <dt>O meni</dt>
                        <dd>{{ $user->about ?: '-' }}</dd>
                        <dt>Potpis</dt>
                        <dd>{{ $user->signature ?: '-' }}</dd>
                    </dl>
                </section>
                @if ($user->owner_of->count())
                    Vlasnik sledećih foruma:
                    <ul>
                        @foreach ($user->owner_of as $_owned_board)
                            <li><a href="{{ route('boards.show', [$_owned_board->address]) }}">{{ $_owned_board->title }}</a></li>
                        @endforeach
                    </ul>
                @endif
                @if ($user->banned_on->count())
                    Banovan na sledećim forumima:
                    <ul>
                        @foreach ($user->banned_on as $_banned_on)
                            <li><a href="{{ route('boards.show', [$_banned_on->address]) }}">{{ $_banned_on->title }}</a></li>
                        @endforeach
                    </ul>
                @endif

                @if ($user->username !== 'admin' || $v_user->username === 'admin')
                    @if ($v_user->id === $user->id || $v_user->is_master)
                        <a class="btn btn-success" href="{{ route('users.edit', [$user->username]) }}">Izmeni profil</a>
                    @endif

                    @if ($v_user->id !== $user->id && !$user->is_banished)
                        @if ($v_user->is_master)
                            <form class="d-inline-block" method="post" action="{{ route('users.master', [$user->id]) }}">
                                @csrf
                                <button class="btn btn-info">{{ $user->is_master ? 'Oduzmi master' : 'Daj master' }}</button>
                            </form>
                            <form class="d-inline-block" method="post" action="{{ route('users.banish', [$user->id]) }}">
                                @csrf
                                <button class="btn btn-danger">Progni</button>
                            </form>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
@stop
