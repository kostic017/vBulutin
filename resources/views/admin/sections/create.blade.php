@extends('admin.base')

@section('more-styles')
    <link rel="stylesheet" href="{{ asset('lib/sceditor/themes/default.min.css') }}">
@stop

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('Create New Section') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('sections.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="title">{{ __('Title') }}</label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}" required>

                    @if ($errors->has('title'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea class="sceditor" name="description" id="description" value="{{ old('description') }}"></textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            {{ __('Create New Section') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@stop

@section('more-scripts')
    @include('admin.includes.sceditor')
@stop
