@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <strong>Kategorija: {{ $category->title }}</strong>
        </div>

        <div class="card-body">
            <table class="table table-striped show">
                <tr>
                    <th>ID</th>
                    <td>{{ $category->id }}</td>
                </tr>
                <tr>
                    <th>{{ __('db.slug') }}</th>
                    <td>{{ $category->slug }}</td>
                </tr>
                <tr>
                    <th>{{ __('db.description') }}</th>
                    <td>
                        <div>{!! $category->description ? BBCode::parse($category->description) : '-' !!}</div>
                    </td>
                </tr>
                <tr>
                    <th>{{ __('db.position') }}</th>
                    <td>{{ $category->position }}</td>
                </tr>
                <tr>
                    <th>{{ __('db.trashed') }}</th>
                    <td>{{ $category->trashed() ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
@stop
