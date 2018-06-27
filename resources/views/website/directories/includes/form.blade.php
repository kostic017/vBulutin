<div class="card-body">
    <form method="post" action="{{ if_route('website.directory.create') ? route('website.directory.store') : route('website.directory.update', ['directory' => $directory->id]) }}">
        @csrf
        @if (if_route('website.directory.edit')) {{ method_field('PUT') }} @endif

        <div class="form-group required">
            <label for="title">Naslov</label>
            <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title', $directory->title ?? '') }}">
            @include('includes.error', ['error_key' => 'title'])
        </div>
        <div class="form-group">
            <label for="sceditor">{{ __('db.description') }}</label>
            <textarea id="sceditor" name="description">{{ old('description', $directory->description ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Sačuvaj</button>
        @include('includes.sceditor')
    </form>
</div>
