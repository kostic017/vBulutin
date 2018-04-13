@extends('admin.base')

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ $section->title }}</strong>
        </div>

        <div class="card-body">
            <table class="table table-striped info">
                <tr>
                    <td>ID</td>
                    <td>{{ $section->id }}</td>
                </tr>
                <tr>
                    <td>Slug</td>
                    <td>{{ $section->slug }}</td>
                </tr>
                <tr>
                    <td>Opis</td>
                    <td>
                        <div>{!! $section->description ? BBCode::convertToHtml($section->description) : 'Nema opisa.' !!}</div>
                    </td>
                </tr>
                <tr>
                    <td>Pozicija</td>
                    <td>{{ $section->position }}</td>
                </tr>
                <tr>
                    <td>Obrisan</td>
                    <td>{{ $section->deletedAt ?? '-' }}</td>
                </tr>
            </table>
        </div>

    </div>
@stop
