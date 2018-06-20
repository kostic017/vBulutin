<div class="card-body">
    <form method="post" action="{{ active_class(if_route('website.directories.add'), '') }}">
        <div class="form-group required">
            <label for="title">Naziv</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
        </div>
        <div class="form-group required">
            <label for="url">URL</label>
            <input type="text" class="form-control" id="url" name="url" aria-describedby="url-help" value="{{ old('url') }}">
            <small id="url-help" class="form-text text-muted">Web adresa do Vašeg foruma će biti <code>{{ route('board.public.show', ['board_name' => 'url']) }}</code>. Ne stavljajte razmake.</small>
        </div>
        <div class="form-group">
            <label for="directory">Direktorijum</label>
            <select class="form-control" {{ isset($force_directory) ? "disabled" : "" }}>
                @foreach ($directories as $directory)
                    @php(
                        $selected = isset($force_directory) ?
                            ($force_directory->id === $directory->id ? "selected" : "") :
                            (old('directory') === $directory->id ? "selected" : "")
                    )
                    <option value="{{ $directory->id }}" {{ $selected }}>{{ $directory->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group form-check">
            <input type="hidden" name="visible" value="0">
            <input type="checkbox" class="form-check-input" id="visible" name="visible" value="1" {{ old('visible') === "1" || old('visible') === null ? "checked" : "" }}>
            <label class="form-check-label" for="visible">Vidljiv</label>
        </div>
        <div class="form-group">
            <label for="sceditor">{{ __('db.description') }}</label>
            <textarea id="sceditor" name="description">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Sačuvaj</button>
    </form>
</div>

@include('includes.sceditor')
