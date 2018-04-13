@extends('admin.base')

@section('more-styles')
    <link rel="stylesheet" href="{{ asset('lib/sceditor/themes/default.min.css') }}">
@stop

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('Edit Forum') }}</strong>
        </div>

        <div class="card-body">
            <p>Sekcija i natforum se mogu promeniti samo preko stranice za <a href="{{ route('admin.positions') }}">pozicioniranje</a>.</p>
            <form action="{{ route('forums.update', ['forums' => $forum->id]) }}" method="post">
                @csrf
                {{ method_field('PUT') }}

                <div class="form-group">
                    <label for="title">{{ __('Title') }} <span class="text-danger font-weight-bold">*</span></label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') ?? $forum->title }}" required>

                    @if ($errors->has('title'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea class="sceditor" name="description" id="description">{{ old('description') ?? $forum->description }}</textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            {{ __('Edit Forum') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@stop

@section('more-scripts')
    <script src="{{ asset('js/admin/force-section.js') }}"></script>
    @include('admin.includes.sceditor')
@stop
