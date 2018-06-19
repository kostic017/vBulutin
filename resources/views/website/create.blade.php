@extends('layouts.website')

@section('content')
    <div class="card-body">
        <form>
            <div class="form-group required">
                <label for="title">Naziv</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
            </div>
            <div class="form-group required">
                <label for="url">URL</label>
                <input type="text" class="form-control" id="url" name="url" aria-describedby="url-help" value="{{ old('url') }}">
                <small id="url-help" class="form-text text-muted">Web adresa do Vašeg foruma će biti <code>{{ route('front.index', ['board_name' => 'url']) }}</code>. Ne stavljajte razmake.</small>
            </div>
            <div class="form-group form-check">
                <input type="hidden" name="visible" value="0">
                <input type="checkbox" class="form-check-input" id="visible" name="visible" value="1" {{ old('visible') === "1" || old('visible') === null ? "checked" : "" }}>
                <label class="form-check-label" for="visible">Vidljiv odmah</label>
            </div>
            <div class="form-group">
                <label for="sceditor">{{ __('db.description') }}</label>
                <textarea id="sceditor" name="description">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Napravi</button>
        </form>
    </div>

    @include('includes.sceditor')
@stop
