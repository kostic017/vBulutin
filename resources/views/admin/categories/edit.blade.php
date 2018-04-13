@extends('admin.base')

@category('more-styles')
    <link rel="stylesheet" href="{{ asset('lib/sceditor/themes/default.min.css') }}">
@stop

@category('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('Edit category') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('categories.update', ['categories' => $category->id]) }}" method="post">
                @csrf
                {{ method_field('PUT') }}

                <div class="form-group">
                    <label for="title">{{ __('Title') }} <span class="text-danger font-weight-bold">*</span></label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') ?? $category->title }}" required>

                    @if ($errors->has('title'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea class="sceditor" name="description" id="description">{{ old('description') ?? $category->description }}</textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            {{ __('Edit category') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@stop

@category('more-scripts')
    <script src="{{ asset('js/admin/force-category.js') }}"></script>
    @include('admin.includes.sceditor')
@stop
