@extends('layouts.admin')

@section('content')
    <form id="index" action="{{ route("admin.{$table}.index") }}" method="get"></form>

    <div class="admin-actions">

        <div>
            <a href="{{ route("admin.{$table}.create") }}" class="btn btn-primary">
                {{ $table === 'categories' ? __('admin.create-category') : __('admin.create-forum') }}
            </a>
            <select name="perPage" class="form-control">
                <option value="0" {{ !$perPage ? 'selected' : '' }}>&infin;</option>
                @for ($i = $step; $i <= $max; $i += $step)
                    <option value="{{ $i }}" {{ $perPage === $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            <select>
        </div>

        <div class="filter-search">
            <div class="search text-nowrap">
                <input type="text" name="search_query" class="form-control" value="{{ $searchQuery }}">
                <button type="button" name="search_clear" class="btn btn-secondary"><i class="fas fa-fw fa-times"></i></button>
                <button type="button" name="search_submit" class="btn btn-secondary active"><i class="fas fa-fw fa-search"></i></button>
            </div>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary {{ active_class($filter === 'all') }}">
                    <input type="radio" name="filter" autocomplete="off" {{ $filter === 'all' ? 'checked' : '' }} value="all"> {{ __('admin.all') }}
                </label>
                <label class="btn btn-secondary {{ active_class($filter === 'active') }}">
                    <input type="radio" name="filter" autocomplete="off" {{ $filter === 'active' ? 'checked' : '' }} value="active"> {{ __('admin.active') }}
                </label>
                <label class="btn btn-secondary {{ active_class($filter === 'trashed') }}">
                    <input type="radio" name="filter" autocomplete="off" {{ $filter === 'trashed' ? 'checked' : '' }} value="trashed"> {{ __('admin.trashed') }}
                </label>
            </div>
        </div>

    </div>

    @if ($perPage > 0)
        <div class="row justify-content-center">
            {{ $rows->appends('perPage', $perPage)->appends('filter', $filter)->links() }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped sections" data-name="{{ $table }}">
            <thead class="text-nowrap">
                <tr>
                    @th_sections_sort(id)
                    @th_sections_sort(title)
                    @if ($table === 'forums')
                        @th_sections_sort(category)
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
                            <td>{{ $row->category }}</td>
                        @endif

                        <td>
                            <a href="{{ route("admin.{$table}.show", [$table => $row->slug]) }}" class="btn btn-xs btn-success">
                                {{ __('admin.view') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route("admin.{$table}.edit", [$table => $row->id]) }}" class="btn btn-xs btn-info">
                                {{ __('admin.edit') }}
                            </a>
                        </td>
                        <td>
                            @if ($row->trashed())
                                <form action="{{ route("admin.{$table}.restore", [$table => $row->id]) }}" method="post">
                                    @csrf
                                    <button class="btn btn-xs btn-danger" type="submit">{{ __('admin.restore') }}</button>
                                </form>
                            @else
                                <form action="{{ route("admin.{$table}.destroy", [$table => $row->id]) }}" method="post">
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

    <script>$(() => { sectionsTable(); });</script>
@stop
