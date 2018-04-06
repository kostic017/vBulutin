@extends('admin.base')

@section('more-styles')
    <link rel="stylesheed" href="{{ asset('css/summernote.css') }}">
@stop

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>Napravi novu sekciju</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('sections.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="title">Naslov</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Opis</label>
                    <textarea name="description" id="description" cols="5" rows="5" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-success" type="submit">
                            Napravi sekciju
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
@stop

@section('more-scripts')
    <script src="{{ asset('js/summernote.min.js') }}"></script>
@stop
