@extends('layouts.website')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Napravi forum</h2>
        </div>
        @include('admin.includes.board-form')
    </div>
@stop
