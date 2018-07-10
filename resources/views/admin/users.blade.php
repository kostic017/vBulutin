@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <p>Vlasnik ovog foruma je <a href="{{ route_user_show($board->owner) }}">{{ $board->owner->username }}</a>.</p>
            <p>Master administratori i vlasnik foruma nisu prikazani u tabeli.</p>

            <form action="" method="get" class="mb-3">
                <section class="btn-group btn-group-toggle" data-toggle="buttons">
                    @if ($page === 'admins')
                        <label class="btn btn-secondary {{ active_class($user_group === 'all') }}">
                            <input type="radio" name="user_group" value="all" {{ active_class($user_group === 'all', 'checked') }}> Svi
                        </label>
                        <label class="btn btn-secondary {{ active_class($user_group === 'nonadmins') }}">
                            <input type="radio" name="user_group" value="nonadmins" {{ active_class($user_group === 'nonadmins', 'checked') }}> Nisu administratori
                        </label>
                        <label class="btn btn-secondary {{ active_class($user_group === 'admins') }}">
                            <input type="radio" name="user_group" value="admins" {{ active_class($user_group === 'admins', 'checked') }}> Administratori
                        </label>
                    @elseif ($page === 'banned')
                        <label class="btn btn-secondary {{ active_class($user_group === 'all') }}">
                            <input type="radio" name="user_group" value="all" {{ active_class($user_group === 'all', 'checked') }}> Svi
                        </label>
                        <label class="btn btn-secondary {{ active_class($user_group === 'nonbanned') }}">
                            <input type="radio" name="user_group" value="nonbanned" {{ active_class($user_group === 'nonbanned', 'checked') }}> Nisu banovani
                        </label>
                        <label class="btn btn-secondary {{ active_class($user_group === 'banned') }}">
                            <input type="radio" name="user_group" value="banned" {{ active_class($user_group === 'banned', 'checked') }}> Banovani
                        </label>
                    @endif
                </section>
            </form>

            {{ $users->links() }}

            @if (!$users->count())
                Nema ničeg ovde...
            @else
                <table class="table table-light table-striped table-hover">
                    <thead class="thead-dark text-nowrap">
                        <tr>
                            <th>ID</th>
                            <th>Korisničko ime</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $_user)
                            @if ($_user->id !== Auth::id())
                                <tr>
                                    <td>{{ $_user->id }}</td>
                                    <td>{{ $_user->username }}</td>
                                    <td>
                                        <a href="{{ route('users.show', [$_user->username]) }}" class="btn btn-primary" title="Prikaži profil"><i class="fas fa-eye"></i></a>
                                        @if ($page === 'admins')
                                            <form class="d-inline-block" method="post" action="{{ route('users.admin', [$board->address, $_user->id]) }}">
                                                @csrf
                                                @if ($_user->is_admin_of($board))
                                                    <button class="btn btn-danger">Oduzmi admin</button>
                                                @else
                                                    <button class="btn btn-info">Daj admin</button>
                                                @endif
                                            </form>
                                        @elseif ($page === 'banned')
                                            <form class="d-inline-block" method="post" action="{{ route('users.ban', [$board->address, $_user->id]) }}">
                                                @csrf
                                                @if ($_user->is_banned_on($board))
                                                    <button class="btn btn-info">Odbanuj</button>
                                                @else
                                                    <button class="btn btn-danger">Banuj</button>
                                                @endif
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif

            <script>
                $(function() {
                    $(".btn-group input[type=radio]").change(function() {
                        $(this).closest("form").submit();
                    })
                });
            </script>
        </div>
    </div>
@stop
