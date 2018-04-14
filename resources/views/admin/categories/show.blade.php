@extends('admin.base')

@section('content')
    <div class="card">

        <div class="card-header">
            <strong>{{ $category->title }}</strong>
        </div>

        <div class="card-body">
            <table class="table table-striped show">
                <tr>
                    <td>ID</td>
                    <td>{{ $category->id }}</td>
                </tr>
                <tr>
                    <td>{{ __('db.slug') }}</td>
                    <td>{{ $category->slug }}</td>
                </tr>
                <tr>
                    <td>{{ __('db.description') }}</td>
                    <td>
                        <div>{!! $category->description ? BBCode::convertToHtml($category->description) : 'Nema opisa.' !!}</div>
                    </td>
                </tr>
                <tr>
                    <td>{{ __('db.position') }}</td>
                    <td>{{ $category->position }}</td>
                </tr>
                <tr>
                    <td>{{ __('db.trashed') }}</td>
                    <td>{{ $category->deletedAt ?? '-' }}</td>
                </tr>
            </table>
        </div>

    </div>
@stop
