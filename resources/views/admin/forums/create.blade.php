@extends('admin.base')

@section('more-styles')
    <link rel="stylesheet" href="{{ asset('lib/sceditor/themes/default.min.css') }}">
@stop

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('Create New Forum') }}</strong>
        </div>

        <div class="card-body">
            @if ($sections->isEmpty())
                <p>{{ __('You have to create at least one section') }}.</p>
            @else
                <form action="{{ route('forums.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">{{ __('Title') }} <span class="text-danger font-weight-bold">*</span></label>
                        <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}" required>

                        @if ($errors->has('title'))
                            <span class="invalid-feedback" style="display:block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="section_id">{{ __('Section') }} <span class="text-danger font-weight-bold">*</span></label>
                        <select name="section_id" id="section_id" class="form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }}" required>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ old('section_id') === $section->id ? 'selected' : '' }}>{{ $section->title }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('section_id'))
                            <span class="invalid-feedback" style="display:block">
                                <strong>{{ $errors->first('section_id') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="parent_id">{{ __('Parent Forum') }}</label>
                        <select name="parent_id" id="parent_id" class="form-control">
                            <option value="" selected></option>
                            @foreach ($rootForums as $rootForum)
                                <option value="{{ $rootForum->id }}" {{ old('parent_id') === $rootForum->id ? 'selected' : '' }}>{{ $rootForum->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">{{ __('Description') }}</label>
                        <textarea class="sceditor" name="description" id="description">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <div class="text-center">
                            <button class="btn btn-success" type="submit">
                                {{ __('Create New Forum') }}
                            </button>
                        </div>
                    </div>

                </form>
            @endif

        </div>

    </div>
@stop

@section('more-scripts')
    <script src="{{ asset('js/admin/force-section.js') }}"></script>
    @include('admin.includes.sceditor')
@stop
