@extends('admin.base')

@section("more-content")
    <a href="{{ route("{$table}.create") }}" class="btn btn-primary">Create New Section</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <a href="javascript:void(0)" class="sort-link">
                        id <span class='icon sort-icon {{ active_class($sortColumn == 'id', "ic_s_{$sortOrder}") }}'></span>
                    </a>
                </th>
                <th>
                    <a href="javascript:void(0)" class="sort-link">
                        title <span class='icon sort-icon {{ active_class($sortColumn == 'title', "ic_s_{$sortOrder}") }}'></span>
                    </a>
                </th>
                <th colspan="3">&nbsp;</th>
            </tr>
        </thead>
        <tbody class="table-hover">
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->title }}</td>
                    <td>
                        <a href="{{ route("{$table}.show", ["{$table}" => $row->id]) }}" class="btn btn-xs btn-success">View</a>
                    </td>
                    <td>
                        <a href="{{ route("{$table}.edit", ["{$table}" => $row->id]) }}" class="btn btn-xs btn-info">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route("{$table}.destroy", ["{$table}" => $row->id]) }}" method="post">
                            @csrf
                            {{ method_field('DELETE') }}
                            <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('more-scripts')
    <script src="{{ asset('js/admin/table.js') }}"></script
@stop
