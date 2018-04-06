@extends('admin.base')

@section('more-styles')
    <link rel="stylesheed" href="{{ asset('css/summernote.css') }}">
@stop

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>Napravi novi forum</strong>
        </div>

        <div class="card-body">
            @if (empty($sections))
                <p>Morate napraviti bar jednu sekciju.</p>
            @else
                <form action="{{ route('forums.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">Naslov <span class="text-danger font-weight-bold">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="section_id">Sekcija <span class="text-danger font-weight-bold">*</span></label>
                        <select name="sectiond_id" id="section_id" class="form-control">
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="parent_id">Natforum</label>
                        <select name="parent_id" id="parent_id" class="form-control">
                            <option value="" selected></option>
                            @foreach ($rootForums as $rootForum)
                                <option value="{{ $rootForum->id }}">{{ $rootForum->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Opis</label>
                        <textarea name="description" id="description" cols="5" rows="5" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <div class="text-center">
                            <button class="btn btn-success" type="submit">
                                Napravi forum
                            </button>
                        </div>
                    </div>

                </form>
            @endif

        </div>

    </div>
@stop

@section('more-scripts')
    <script src="{{ asset('js/summernote.min.js') }}"></script>
@stop
