@extends('layouts.admin')

@section('content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('admin.create-category') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="title">{{ __('db.title') }}</label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}">

                    @if ($errors->has('title'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="sceditor">{{ __('db.description') }}</label>
                    <textarea id="sceditor" name="description">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            {{ __('admin.create-category') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>

    @include('includes.sceditor')
@stop

