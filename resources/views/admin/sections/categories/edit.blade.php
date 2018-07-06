@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <strong>{{ __('admin.edit-category') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('categories.update', [$board->address, $category->id]) }}" method="post">
                @csrf
                {{ method_field('PUT') }}

                <div class="form-group required">
                    <label for="title">{{ __('db.title') }}</label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title', $category->title) }}">
                    @include('includes.error', ['error_key' => 'title'])
                </div>

                <div class="form-group">
                    <label for="sceditor">{{ __('db.description') }}</label>
                    <textarea id="sceditor" name="description">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            {{ __('admin.edit-category') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @include('includes.sceditor')
@stop

