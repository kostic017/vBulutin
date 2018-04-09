@extends('admin.base')

@section('more-styles')
    <link rel="stylesheet" href="{{ asset('css/admin/table.css') }}">
@stop

@section("more-content")

    <div class="row">
        <div class="col">
            <a href="{{ route("{$table}.create") }}" class="btn btn-primary">
                {{ $table === 'sections' ? __('Create New Section') : __('Create New Forum') }}
            </a>
        </div>
        <div class="col-3 col-lg-2 justify-content-right">
            <form action="{{ route("{$table}.index") }}" method="get">
                <select name="perPage" class="form-control">
                    <option value="0" {{ $perPage === 0 ? 'selected' : '' }}>&infin;</option>
                    <option value="10" {{ $perPage === 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $perPage === 20 ? 'selected' : '' }}>20</option>
                    <option value="30" {{ $perPage === 30 ? 'selected' : '' }}>30</option>
                    <option value="40" {{ $perPage === 40 ? 'selected' : '' }}>40</option>
                    <option value="50" {{ $perPage === 50 ? 'selected' : '' }}>50</option>
                    <option value="60" {{ $perPage === 60 ? 'selected' : '' }}>60</option>
                    <option value="70" {{ $perPage === 70 ? 'selected' : '' }}>70</option>
                    <option value="80" {{ $perPage === 80 ? 'selected' : '' }}>80</option>
                    <option value="90" {{ $perPage === 90 ? 'selected' : '' }}>90</option>
                    <option value="100" {{ $perPage === 100 ? 'selected' : '' }}>100</option>
                <select>
            </form>
        </div>
    </div>

    <table class="table table-striped" data-name="{{ $table }}">
        <thead>
            <tr>
                <th>
                    <a href="javascript:void(0)" class="sort-link" data-column="id">
                        ID @sort_icon('id')
                    </a>
                </th>
                <th>
                    <a href="javascript:void(0)" class="sort-link" data-column="title">
                        {{ __('Title') }} @sort_icon('title')
                    </a>
                </th>
                <th colspan="3">&nbsp;</th>
            </tr>
        </thead>
        <tbody class="table-hover">
            @foreach ($rows as $row)
                <tr id="row-{{ $row->id }}">
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->title }}</td>
                    <td>
                        <a href="{{ route("{$table}.show", ["{$table}" => $row->id]) }}" class="btn btn-xs btn-success">
                            {{ __('View') }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route("{$table}.edit", ["{$table}" => $row->id]) }}" class="btn btn-xs btn-info">
                            {{ __('Edit') }}
                        </a>
                    </td>
                    <td>
                        <form action="{{ route("{$table}.destroy", ["{$table}" => $row->id]) }}" method="post">
                            @csrf
                            {{ method_field('DELETE') }}
                            <button class="btn btn-xs btn-danger" type="submit">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($perPage > 0)
        <div class="row justify-content-center">
            {{ $rows->appends('perPage', $perPage)->links() }}
        </div>
    @endif

    <div id="overlay"></div>
@stop

@section('more-scripts')
    <script src="{{ asset('js/admin/table.js') }}"></script>
@stop
