@extends('admin.base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('lib/sceditor/themes/default.min.css') }}">
@stop

@section('scripts')
    @include('includes.sceditor')
@stop

@section('content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('buttons.create_category') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="title">{{ __('db.title') }}</label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}" required>

                    @if ($errors->has('title'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">{{ __('db.description') }}</label>
                    <textarea class="sceditor" name="description" id="description">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            {{ __('buttons.create_category') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@stop

