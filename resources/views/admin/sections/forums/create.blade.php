@extends('layouts.admin')

@section('scripts')
    <script src="{{ asset('js/admin/force-category.js') }}"></script>
    @include('includes.sceditor')
@overwrite

@section('content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('admin.create-forum') }}</strong>
        </div>

        <div class="card-body">
            @if ($categories->isEmpty())
                <p>{{ __('admin.category-needed') }}</p>
            @else
                <form action="{{ route('forums.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">{{ __('db.title') }} <span class="text-danger font-weight-bold">*</span></label>
                        <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}">

                        @if ($errors->has('title'))
                            <span class="invalid-feedback" style="display:block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="category_id">{{ __('db.category') }} <span class="text-danger font-weight-bold">*</span></label>
                        <select name="category_id" id="category_id" class="form-control{{ $errors->has('category_id') ? ' is-invalid' : '' }}">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') === $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('category_id'))
                            <span class="invalid-feedback" style="display:block">
                                <strong>{{ $errors->first('category_id') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="parent_id">{{ __('db.parent-forum') }}</label>
                        <select name="parent_id" id="parent_id" class="form-control">
                            <option value="" selected></option>
                            @foreach ($rootForums as $rootForum)
                                <option value="{{ $rootForum->id }}" {{ old('parent_id') === $rootForum->id ? 'selected' : '' }}>{{ $rootForum->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">{{ __('db.description') }}</label>
                        <textarea class="sceditor" name="description" id="description">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <div class="text-center">
                            <button class="btn btn-success" type="submit">
                                {{ __('admin.create-forum') }}
                            </button>
                        </div>
                    </div>

                </form>
            @endif

        </div>

    </div>
@stop
