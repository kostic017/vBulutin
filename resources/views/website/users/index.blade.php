@extends('layouts.website')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="get" class="dynamic-data" action="{{ route('users.index.public') }}">
                <input type="hidden" name="sort_column" value="username">
                <input type="hidden" name="sort_order" value="asc">

                <section class="text-center">
                    <button type="submit" class="btn btn-success">Primeni</button>
                </section>

                <fieldset>
                    <section class="paginate d-flex">
                        <select name="per_page" class="form-control">
                            <option value="0" {{ !$per_page ? 'selected' : '' }}>&infin;</option>
                            @for ($i = $pagination_step; $i <= $pagination_max; $i += $pagination_step)
                                <option value="{{ $i }}" {{ $per_page === $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        <select>
                        @if ($per_page > 0)
                            {{-- {{ $users->appends('per_page', $per_page)->links() }} --}}
                        @endif
                    </section>
                    <section class="search">
                        <input type="text" name="search_query" value="" class="form-control" placeholder="Pretraži...">
                        <select name="search_field" class="form-control">
                            @foreach ($columns as $_column => $_)
                                <option value="{{ $_column }}">{{ trans("db.users.$_column") }}</option>
                            @endforeach
                        <select>
                    </section>
                </fieldset>
                <fieldset>
                    <section class="user-status d-flex">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary active">
                                <input type="radio" name="user_status" checked> Svi
                            </label>
                            <label class="btn btn-secondary">
                                <input type="radio" name="user_status"> Aktivni
                            </label>
                            <label class="btn btn-secondary">
                                <input type="radio" name="user_status"> Banovani
                            </label>
                            <label class="btn btn-secondary">
                                <input type="radio" name="user_status"> Prognani
                            </label>
                        </div>
                    </section>
                    <section class="user-group btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="user_group" value="all" checked> Svi
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="user_group" value="simple_users"> Obični korisnici
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="user_group" value="simple_admins"> Obični administratori
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="user_group" value="forum_owners"> Vlasnici forumova
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="user_group" value="master_admins"> Master administratori
                        </label>
                    </section>
                </fieldset>
                <fieldset>
                    <section class="columns">
                        @foreach ($columns as $_column => $_checked)
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="show_columns" class="form-check-input" value="{{ $_column }}"{{ $_checked ? ' checked' : ''}}> {{ trans("db.users.$_column") }}
                                </label>
                            </div>
                        @endforeach
                    </section>
                </fieldset>
            </form>

            <table class="table table-light table-striped table-hover users">
                <thead class="thead-dark text-nowrap">
                    <tr>
                        @foreach ($columns as $_column => $_checked)
                            @if ($_checked)
                                <th>{{ trans("db.users.$_column") }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $_user)
                        <tr>
                            @foreach ($columns as $_column => $_checked)
                                @if ($_checked)
                                    <td>{{ $_user->{$_column} }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
