<div class="card-body">
    <form method="post" action="{{ if_route('boards.create') ? route('boards.store') : route('boards.update', [$board->address]) }}">
        @csrf
        @if (if_route('admin.index'))
            {{ method_field('PUT') }}
        @endif

        <div class="form-group required">
            <label for="title">Naziv</label>
            <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title', isset($board) ? $board->title : '') }}">
            @include('includes.error', ['error_key' => 'title'])
        </div>
        <div class="form-group required">
            <label for="address">Adresa</label>
            <input type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" id="address" name="address" aria-describedby="address-help" value="{{ old('address', isset($board) ? $board->address : 'adresa') }}">
            <small id="address-help" class="form-text text-muted">Web adresa do Vašeg foruma će biti <code>http://<span id="preview"></span>.{{ config('app.domain') }}/</code>. Dozvoljena su slova, brojevi, donja crta i crtica.</small>
            @include('includes.error', ['error_key' => 'address'])
        </div>
        <div class="form-group">
            <label for="directory_id">Direktorijum</label>
            <select name="directory_id" id="directory_id" class="form-control{{ $errors->has('directory_id') ? ' is-invalid' : '' }}" {{ isset($force_directory) ? "disabled" : "" }}>
                @foreach ($directories as $_directory)
                    @php(
                        $selected = isset($force_directory) ?
                            ($force_directory->id === $_directory->id ? 'selected' : '') :
                            (old('directory_id', isset($board) ? $board->directory->id : '') == $_directory->id ? 'selected' : '')
                    )
                    <option value="{{ $_directory->id }}" {{ $selected }}>{{ $_directory->title }}</option>
                @endforeach
            </select>
            @if (isset($force_directory))
                <input type="hidden" name="directory_id" value="{{ $force_directory->id }}">
            @endif
        </div>
        <div class="form-group form-check">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" class="form-check-input" id="is_visible" name="is_visible" value="1" {{ old('is_visible', isset($board) ? $board->is_visible : '') == '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="is_visible">Vidljiv</label>
        </div>
        <div class="form-group">
            <label for="sceditor">{{ __('db.description') }}</label>
            <textarea id="sceditor" name="description">{{ old('description', isset($board) ? $board->description : '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Sačuvaj</button>
        @include('includes.sceditor')
    </form>
</div>

<script>
    $(function() {
        $("input[name=address]").on('input', function() {
            $("#preview").html($(this).val());
        }).trigger("input");
    });
</script>
