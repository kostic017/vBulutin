@extends('layouts.admin')

@section('content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('admin.create-forum') }}</strong>
        </div>

        <div class="card-body">
            @if ($categories->isEmpty())
                <p>{{ __('admin.category-needed') }}</p>
            @else
                <form action="{{ route('back.forums.store') }}" method="post">
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
                        <label for="parent_id">{{ __('db.parent_forum') }}</label>
                        <select name="parent_id" id="parent_id" class="form-control">
                            <option value="" selected></option>
                            @foreach ($rootForums as $rootForum)
                                <option value="{{ $rootForum->id }}" {{ old('parent_id') === $rootForum->id ? 'selected' : '' }}
                                    data-category="{{ $rootForum->category_id }}">{{ $rootForum->title }}</option>
                            @endforeach
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
            @endif

        </div>

    </div>

    @include('includes.sceditor')
    <script>$(() => { forceCategory(); });</script>
@stop
