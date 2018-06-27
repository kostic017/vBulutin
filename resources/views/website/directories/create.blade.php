@extends('layouts.website')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Napravi direktorijum</h2>
        </div>
        @include('website.directories.includes.form')
    </div>
@stop
