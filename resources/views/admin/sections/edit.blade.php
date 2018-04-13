@extends('admin.base')

@section('more-styles')
    <link rel="stylesheet" href="{{ asset('lib/sceditor/themes/default.min.css') }}">
@stop

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('Edit section') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('sections.update', ['sections' => $section->id]) }}" method="post">
                @csrf
                {{ method_field('PUT') }}

                <div class="form-group">
                    <label for="title">{{ __('Title') }} <span class="text-danger font-weight-bold">*</span></label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') ?? $section->title }}" required>

                    @if ($errors->has('title'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea class="sceditor" name="description" id="description">{{ old('description') ?? $section->description }}</textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            {{ __('Edit section') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@stop

@section('more-scripts')
    <script src="{{ asset('js/admin/force-section.js') }}"></script>
    @include('admin.includes.sceditor')
@stop
