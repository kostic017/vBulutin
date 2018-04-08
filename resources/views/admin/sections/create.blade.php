@extends('admin.base')

@section('more-styles')
    <link rel="stylesheet" href="{{ asset('lib/summernote/summernote-bs4.css') }}">
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
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea name="description" id="description" cols="5" rows="5"></textarea>
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
    <script src="{{ asset('lib/summernote/summernote-bs4.min.js') }}"></script>

    <script>
        $('textarea').summernote();
    </script>
@stop
