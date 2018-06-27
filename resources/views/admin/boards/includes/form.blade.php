<div class="card-body">
    <form method="post" action="{{ isset($force_directory) ? route('admin.boards.store') : route('admin.board.update') }}">
        @csrf

        <div class="form-group required">
            <label for="title">Naziv</label>
            <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title', $board->title ?? '') }}">
            @include('include.error', ['error_key' => 'title'])
        </div>
        <div class="form-group required">
            <label for="url">URL</label>
            <input type="text" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" id="url" name="url" aria-describedby="url-help" value="{{ old('url', $board->url ?? '') }}">
            <small id="url-help" class="form-text text-muted">Web adresa do Vašeg foruma će biti <code>{{ route('public.show', ['board_url' => 'url']) }}</code>. Dozvoljena slova, brojevi, donje crte i crtice.</small>
            @include('include.error', ['error_key' => 'url'])
        </div>
        <div class="form-group">
            <label for="directory">Direktorijum</label>
            <select class="form-control{{ $errors->has('directory') ? ' is-invalid' : '' }}" {{ isset($force_directory) ? "disabled" : "" }}>
                @foreach ($directories as $directory)
                    @php(
                        $selected = isset($force_directory) ?
                            ($force_directory->id === $directory->id ? 'selected' : '') :
                            (old('directory', $board->directory->id ?? '') === $directory->id ? 'selected' : '')
                    )
                    <option value="{{ $directory->id }}" {{ $selected }}>{{ $directory->title }}</option>
                @endforeach
            </select>
            @if (isset($force_directory))
                <input type="hidden" name="directory" value="{{ $force_directory->id }}">
            @endif
        </div>
        <div class="form-group form-check">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" class="form-check-input" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $board->is_visible ?? '') === '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="is_visible">Vidljiv</label>
        </div>
        <div class="form-group">
            <label for="sceditor">{{ __('db.description') }}</label>
            <textarea id="sceditor" name="description">{{ old('description', $board->description ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Sačuvaj</button>
        @include('includes.sceditor')
    </form>
</div>
