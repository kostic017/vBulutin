@extends('layouts.website')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="get" id="dynamic-users" class="dynamic-data" action="{{ route('users.index.public') }}">
                <input type="hidden" name="sort_column" value="username">
                <input type="hidden" name="sort_order" value="asc">

                <fieldset>
                    <section class="paginate d-flex">
                        <select name="per_page" class="form-control">
                            <option value="0" {{ !$per_page ? 'selected' : '' }}>&infin;</option>
                            @for ($i = $pagination_step; $i <= $pagination_max; $i += $pagination_step)
                                <option value="{{ $i }}" {{ $i === (int)$per_page ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        <select>
                        @if ($per_page > 0)
                            {{ $users->links() }}
                        @endif
                    </section>
                    <section class="search">
                        <input type="text" name="search_query" value="{{ $search_query }}" class="form-control" placeholder="Pretraga...">
                        <select name="search_field" class="form-control">
                            @foreach ($show_columns as $_column => $_)
                                <option value="{{ $_column }}" {{ active_class($search_field === $_column, 'selected') }}>{{ trans("db.columns.$_column") }}</option>
                            @endforeach
                        <select>
                        <button type="submit" class="btn btn-success" title="Pretraži"><i class="fas fa-search"></i></button>
                        <button type="button" name="clear-search" class="btn btn-danger" title="Poništi pretragu"><i class="fas fa-times"></i></button>
                    </section>
                </fieldset>
                <fieldset>
                    <section class="user-status d-flex">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary {{ active_class($user_status === 'all') }}">
                                <input type="radio" name="user_status" value="all" {{ active_class($user_status === 'all', 'checked') }}> Svi
                            </label>
                            <label class="btn btn-secondary {{ active_class($user_status === 'active') }}">
                                <input type="radio" name="user_status" value="active" {{ active_class($user_status === 'active', 'checked') }}> Aktivni
                            </label>
                            <label class="btn btn-secondary {{ active_class($user_status === 'banned') }}">
                                <input type="radio" name="user_status" value="banned" {{ active_class($user_status === 'banned', 'checked') }}> Banovani
                            </label>
                            <label class="btn btn-secondary {{ active_class($user_status === 'banished') }}">
                                <input type="radio" name="user_status" value="banished" {{ active_class($user_status === 'bannished', 'checked') }}> Prognani
                            </label>
                        </div>
                    </section>
                    <section class="user-group btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary {{ active_class($user_group === 'all') }}">
                            <input type="radio" name="user_group" value="all" {{ active_class($user_group === 'all', 'checked') }}> Svi
                        </label>
                        <label class="btn btn-secondary {{ active_class($user_group === 'simpleUsers') }}">
                            <input type="radio" name="user_group" value="simpleUsers" {{ active_class($user_group === 'simpleUsers', 'checked') }}> Obični korisnici
                        </label>
                        <label class="btn btn-secondary {{ active_class($user_group === 'simpleAdmins') }}">
                            <input type="radio" name="user_group" value="simpleAdmins" {{ active_class($user_group === 'simpleAdmins', 'checked') }}> Obični administratori
                        </label>
                        <label class="btn btn-secondary {{ active_class($user_group === 'forumOwners') }}">
                            <input type="radio" name="user_group" value="forumOwners" {{ active_class($user_group === 'forumOwners', 'checked') }}> Vlasnici forumova
                        </label>
                        <label class="btn btn-secondary {{ active_class($user_group === 'masterAdmins') }}">
                            <input type="radio" name="user_group" value="masterAdmins" {{ active_class($user_group === 'masterAdmins', 'checked') }}> Master administratori
                        </label>
                    </section>
                </fieldset>
                <fieldset>
                    <section class="columns">
                        @foreach ($show_columns as $_column => $_shown)
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="show_columns[]" class="form-check-input" value="{{ $_column }}"{{ $_shown ? ' checked' : ''}}> {{ trans("db.columns.$_column") }}
                                </label>
                            </div>
                        @endforeach
                    </section>
                </fieldset>
            </form>

            Broj rezultata: {{ $users->count() }}

            @if ($users->count())
                <table class="table table-light table-striped table-hover users" data-formid="dynamic-users">
                    <thead class="thead-dark text-nowrap">
                        <tr>
                            @foreach ($show_columns as $_column => $_shown)
                                @if ($_shown)
                                    @th_sort
                                @endif
                            @endforeach
                            @auth
                                <th>&nbsp;</th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $_user)
                            <tr>
                                @foreach ($show_columns as $_column => $_shown)
                                    @if ($_shown)
                                        <td><div class="table-cell">
                                            @switch ($_column)
                                                @case('sex')
                                                    {{ $_user->{$_column} ? trans('db.sex.' . $_user->{$_column}) : '' }}
                                                    @break
                                                @case('about')
                                                @case('signature')
                                                    {!! BBCode::parse($_user->{$_column}) !!}
                                                    @break
                                                @default
                                                    {{ $_user->{$_column} }}
                                                    @break
                                            @endswitch
                                        </div></td>
                                    @endif
                                @endforeach
                                @auth
                                    <td>
                                        <a href="{{ route('users.show', [$_user->username]) }}" class="btn btn-primary" title="Prikaži profil"><i class="fas fa-eye"></i></a>
                                    </td>
                                @endauth
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        $(function () {
            const form = $("#dynamic-users");

            $(`select:not([name=search_field]),
                input:not([name=search_query])
            `).on('change', function () {
                form.submit();
            });

            $("button[name=clear-search]").on('click', function() {
                $("input[name=search_query]").val("");
                form.submit();
            });
        });
    </script>

    <script src="{{ asset('js/sorting.js') }}"></script>
@stop
