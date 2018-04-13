@extends('admin.base')

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ $category->title }}</strong>
        </div>

        <div class="card-body">
            <table class="table table-striped info">
                <tr>
                    <td>ID</td>
                    <td>{{ $category->id }}</td>
                </tr>
                <tr>
                    <td>Slug</td>
                    <td>{{ $category->slug }}</td>
                </tr>
                <tr>
                    <td>Opis</td>
                    <td>
                        <div>{!! $category->description ? BBCode::convertToHtml($category->description) : 'Nema opisa.' !!}</div>
                    </td>
                </tr>
                <tr>
                    <td>Pozicija</td>
                    <td>{{ $category->position }}</td>
                </tr>
                <tr>
                    <td>Obrisan</td>
                    <td>{{ $category->deletedAt ?? '-' }}</td>
                </tr>
            </table>
        </div>

    </div>
@stop
