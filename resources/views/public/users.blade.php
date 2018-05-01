@extends('layouts.public')

@section('content')
    <table class="table table-striped table-hover users">
        <thead class="thead-dark">
            <tr>
                <th>Korisničko ime</th>
                <th class="about">O meni</th>
                <th>Pridružio</th>
                <th>Poruke</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td><a href="{{ route('public.user', ['user' => $user->username]) }}">{{ $user->username }}</a></td>
                    <td class="about">{{ limit_words($user->profile()->first()->about, 10) }}</td>
                    <td>{{ extractDate($user->registered_at) }}</td>
                    <td>{{ $user->posts()->get()->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
