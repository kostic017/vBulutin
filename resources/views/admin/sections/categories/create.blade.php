@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <strong>{{ __('admin.create-category') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('categories.store', [$current_board->address]) }}" method="post">
                @csrf

                <div class="form-group required">
                    <label for="title">{{ __('db.title') }}</label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}">
                    @include('includes.error', ['error_key' => 'title'])
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

