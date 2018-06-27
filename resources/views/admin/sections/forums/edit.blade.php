@extends('layouts.admin')

@section('scripts')
    <script>$(() => { forceCategory(); });</script>
@stop

@section('content')
    <div class="card">

        <div class="card-header">
            <strong>{{ __('admin.edit-forum') }}</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.forum.update', ['forum' => $forum->id]) }}" method="post">
                @csrf
                {{ method_field('PUT') }}

                <div class="form-group">
                    <label for="title">{{ __('db.title') }} <span class="text-danger font-weight-bold">*</span></label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title', $forum->title ?? '') }}">
                    @include('includes.error', ['error_key' => 'title'])
                </div>

                <div class="form-group">
                    <label for="sceditor">{{ __('db.description') }}</label>
                    <textarea id="sceditor" name="description">{{ old('description', $forum->description ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            {{ __('admin.edit-forum') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>

    @include('includes.sceditor')
@stop
