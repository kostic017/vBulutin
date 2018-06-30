@extends('layouts.website')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Napravi direktorijum</h2>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('directories.store') }}">
                <div class="form-group required">
                    <label for="title">Naslov</label>
                    <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title') }}">
                    @include('includes.error', ['error_key' => 'title'])
                </div>
                <div class="form-group">
                    <label for="sceditor">{{ __('db.description') }}</label>
                    <textarea id="sceditor" name="description">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Sačuvaj</button>
                @include('includes.sceditor')
            </form>
        </div>
    </div>
@stop
