@extends('layouts.website')

@section('content')
    <div class="card">
        <div class="card-body">
            @include('includes.errors')

            <form class="profile" action="{{ route('users.update', [$user->username]) }}" method="post">
                @csrf
                {{ method_field('PUT') }}

                <fieldset>
                    <div class="form-group">
                        <label for="username">Korisničko ime</label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ $user->username }}" aria-describedby="usernameHelp" {{ !Auth::user()->is_master ? 'disabled' : '' }}>
                        <small id="usernameHelp" class="form-text text-muted">Isključivo master administratori mogu da promene.</small>
                    </div>
                    <div class="form-group form-check">
                        <input type="hidden" name="is_invisible" value="0">
                        <input type="checkbox" name="is_invisible" class="form-check-input" id="is_invisible" {{ $user->is_invisible ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_invisible">Hoću da sam nevidljiv.</label>
                    </div>
                    <div class="form-group">
                        <label for="email">Email adresa</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}">
                    </div>
                    <div class="form-group">
                        <label for="password-current">Trenutna šifra</label>
                        <input type="password" id="password-current" name="password_current" class="form-control" aria-describedby="passwordCurrentHelp">
                        <small id="passwordCurrentHelp" class="form-text text-muted">Morate da upišete svoju trenutnu šifru ako menjate email ili šifru.</small>
                    </div>
                    <div class="form-group">
                        <label for="password">Nova šifra</label>
                        <input type="password" id="password" name="password" class="form-control" aria-describedby="passwordHelp">
                        <small id="passwordHelp" class="form-text text-muted">Ako hoćete novu šifru upišite je ovde i u polje ispod.</small>
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">Potvrdi šifru</label>
                        <input type="password" id="password-confirm" name="password_confirm" class="form-control">
                    </div>
                </fieldset>

                <fieldset>
                    <div class="form-group">
                        <label for="sex">Pol</label>
                        <select class="form-control" name="sex" id="sex">
                            <option value="m" {{ $user->sex === 'm' ? 'selected' : '' }}>Мuški</option>
                            <option value="f" {{ $user->sex === 'f' ? 'selected' : '' }}>Ženski</option>
                            <option value="o" {{ $user->sex === 'o' ? 'selected' : '' }}>Drugo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="birthday">Datum rođenja</label>
                        <input type="date" id="birthday" name="birthday_on" class="form-control" value="{{ $user->birthday_on }}">
                    </div>
                    <div class="form-group">
                        <label for="birthplace">Mesto rođenja</label>
                        <input type="text" id="birthplace" name="birthplace" class="form-control" value="{{ $user->birthplace }}">
                    </div>
                    <div class="form-group">
                        <label for="residence">Prebivalište</label>
                        <input type="text" id="residence" name="residence" class="form-control" value="{{ $user->residence }}">
                    </div>
                    <div class="form-group">
                        <label for="job">Zanimanje</label>
                        <input type="text" id="job" name="job" class="form-control" value="{{ $user->job }}">
                    </div>
                    <div class="form-group">
                        <label for="avatar">Profilna slika</label>
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->username }}" class="avatar avatar-medium">
                        @endif
                        <input type="text" id="avatar" name="avatar" class="form-control" value="{{ $user->avatar }}" placeholder="URL do slike...">
                    </div>
                </fieldset>

                <fieldset>
                    <div class="form-group">
                        <label for="about">O meni</label>
                        <textarea class="form-control" name="about" id="about" rows="3">{{ $user->about }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="signature">Potpis</label>
                        <textarea class="form-control" name="signature" id="signature" rows="3">{{ $user->signature }}</textarea>
                    </div>
                </fieldset>

                <button type="submit" class="btn btn-primary">Promeni</button>
            </form>
        </div>
    </div>
@stop
