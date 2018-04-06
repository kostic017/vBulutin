@section('more-styles')
    <link rel="stylesheed" href="{{ asset('css/summernote.css') }}">
    <link rel="stylesheed" href="{{ asset('css/dropdown.min.css') }}">
@stop
@section('more-scripts')
    <script src="{{ asset('js/dropdown.min.js') }}"></script>
    <script src="{{ asset('js/summernote.min.js') }}"></script>

    <script>
        $('.ui.dropdown').dropdown()
    </script>
@stop
