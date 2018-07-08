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
                @if ($user->owned_boards->count())
                    Vlasnik sledećih foruma:
                    <ul>
                        @foreach ($user->owned_boards as $_owned_board)
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
                @if ($v_user->is_master || $v_user->id === $user->id)
                    <a class="btn btn-success" href="{{ route('users.edit', [$user->username]) }}">Izmeni profil</a>
                @endif
                @if ($v_user->is_master && $v_user->id !== $user->id && !$user->is_banished)
                    <form class="d-inline-block" method="post" action="{{ route('users.banish', [$user->id]) }}">
                        @csrf
                        <button class="btn btn-danger">Progni</button>
                    </form>
                @endif
                @if ($v_user->id !== $user->id && $v_user->owned_boards->count())
                    <p class="mt-3 mb-0">Selektujte forum i pomoću strelica ga premestite u željeno polje.</p>
                    <form method="post" action="{{ route('users.ban', [$user->id]) }}">
                        @csrf

                        <div class="banning">
                            <div class="form-group not-banned-on">
                                <select id="not-banned-on" name="not_banned_on[]" multiple class="form-control">
                                    @foreach ($v_user->owned_boards as $_board)
                                        @if (!$user->is_banned_on($_board->id))
                                            <option value="{{ $_board->id }}">{{ $_board->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="not-banned-on">Nije banovan na</label>
                            </div>
                            <div class="buttons">
                                <button id="move-right" type="button" class="btn btn-warning">
                                    <i class="fas fa-long-arrow-alt-right"></i>
                                </button>
                                <button id="move-left" type="button" class="btn btn-warning">
                                    <i class="fas fa-long-arrow-alt-left"></i>
                                </button>
                            </div>
                            <div class="form-group banned-on">
                                <select id="banned-on" name="banned_on[]" multiple class="form-control">
                                    @foreach ($v_user->owned_boards as $_board)
                                        @if ($user->is_banned_on($_board->id))
                                            <option value="{{ $_board->id }}">{{ $_board->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="banned-on">Banovan je na</label>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Primeni</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(function () {
            const bannedOn = $("#banned-on");
            const notBannedOn = $("#not-banned-on");

            $("#move-right").click(function() {
                $("option:selected", notBannedOn).appendTo(bannedOn);
            });

            $("#move-left").click(function() {
                $("option:selected", bannedOn).appendTo(notBannedOn);
            });
        });
    </script>
@stop
