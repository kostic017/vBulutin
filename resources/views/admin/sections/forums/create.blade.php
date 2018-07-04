@extends('layouts.admin')

@section('content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('admin.create-forum') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('forums.store', [request()->route('board_address')]) }}" method="post">
                @csrf

                <div class="form-group required">
                    <label for="title">{{ __('db.title') }}</label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}">
                    @include('includes.error', ['error_key' => 'title'])
                </div>

                <div class="form-group required">
                    <label for="category_id">{{ __('db.category') }}</label>
                    <select name="category_id" id="category_id" class="form-control" readonly>
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="parent_id">{{ __('db.parent_forum') }}</label>
                    <select name="parent_id" id="parent_id" class="form-control" readonly>
                        @if ($parent_forum)
                            <option value="{{ $parent_forum->id }}">{{ $parent_forum->title }}</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="sceditor">{{ __('db.description') }}</label>
                    <textarea id="sceditor" name="description">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            {{ __('admin.create-forum') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @include('includes.sceditor')
@stop
