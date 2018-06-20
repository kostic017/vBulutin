@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <strong>{{ __('admin.edit-category') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('board.admin.categories.update', ['category' => $category->id]) }}" method="post">
                @csrf
                {{ method_field('PUT') }}

                <div class="form-group">
                    <label for="title">{{ __('db.title') }} <span class="text-danger font-weight-bold">*</span></label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') ?? $category->title }}">

                    @if ($errors->has('title'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="sceditor">{{ __('db.description') }}</label>
                    <textarea id="sceditor" name="description">{{ old('description') ?? $category->description }}</textarea>
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

