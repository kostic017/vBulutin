@php ($max = (int)config('custom.pagination.max'))
@php ($step = (int)config('custom.pagination.step'))

@extends('layouts.admin')

@section('scripts')
    <script src="{{ asset('js/admin/sections-table.js') }}"></script>
@stop

@section("content")
    <form id="index" action="{{ route("{$table}.index") }}" method="get"></form>

    <div class="actions row">
        <div class="buttons col">
            <a href="{{ route("{$table}.create") }}" class="btn btn-primary">
                {{ $table === 'categories' ? __('admin.create-category') : __('admin.create-forum') }}
            </a>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary {{ active_class($filter === 'all') }}">
                    <input type="radio" name="filter" autocomplete="off" {{ active_class($filter === 'all', 'checked') }} value="all"> {{ __('admin.all') }}
                </label>
                <label class="btn btn-secondary {{ active_class($filter === 'active') }}">
                    <input type="radio" name="filter" autocomplete="off" {{ active_class($filter === 'active', 'checked') }} value="active"> {{ __('admin.active') }}
                </label>
                <label class="btn btn-secondary {{ active_class($filter === 'trashed') }}">
                    <input type="radio" name="filter" autocomplete="off" {{ active_class($filter === 'trashed', 'checked') }} value="trashed"> {{ __('admin.trashed') }}
                </label>
            </div>
        </div>
        <div class="menu col">
            <select name="perPage" class="form-control">
                <option value="0" {{ !$perPage ? 'selected' : '' }}>&infin;</option>
                @for ($i = $step; $i <= $max; $i += $step)
                    <option value="{{ $i }}" {{ $perPage === $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            <select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped sections" data-name="{{ $table }}">
            <thead class="text-nowrap">
                <tr>
                    <th>
                        <a href="#" class="sort-link" data-column="id" {!! active_class($sortColumn === 'id', "data-order='{$sortOrder}'") !!}>
                            ID <span class="icon"></span>
                        </a>
                    </th>
                    <th>
                        <a href="#" class="sort-link" data-column="title" {!! active_class($sortColumn === 'title', "data-order='{$sortOrder}'") !!}>
                            {{ __('db.title') }}  <span class="icon"></span>
                        </a>
                    </th>

                    @if ($table === 'forums')
                        <th>
                            <a href="#" class="sort-link" data-column="category" {!! active_class($sortColumn === 'category', "data-order='{$sortOrder}'") !!}>
                                {{ __('db.category') }} <span class="icon"></span>
                            </a>
                        </th>
                    @endif

                    <th colspan="3">&nbsp;</th>
                </tr>
            </thead>
            <tbody class="table-hover">
                @foreach ($rows as $row)
                    <tr id="row-{{ $row->id }}">
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->title }}</td>

                        @if ($table === 'forums')
                            <td>{{ $row->category_title }}</td>
                        @endif

                        <td>
                            <a href="{{ route("{$table}.show", ["{$table}" => $row->id]) }}" class="btn btn-xs btn-success">
                                {{ __('admin.view') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route("{$table}.edit", ["{$table}" => $row->id]) }}" class="btn btn-xs btn-info">
                                {{ __('admin.edit') }}
                            </a>
                        </td>
                        <td>
                            @if ($row->trashed())
                                <form action="{{ route("{$table}.restore", ["{$table}" => $row->id]) }}" method="post">
                                    @csrf
                                    <button class="btn btn-xs btn-danger" type="submit">{{ __('admin.restore') }}</button>
                                </form>
                            @else
                                <form action="{{ route("{$table}.destroy", ["{$table}" => $row->id]) }}" method="post">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-xs btn-danger" type="submit">{{ __('admin.delete') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($perPage > 0)
        <div class="row justify-content-center">
            {{ $rows->appends('perPage', $perPage)->appends('filter', $filter)->links() }}
        </div>
    @endif
@stop
