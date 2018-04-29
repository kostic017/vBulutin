<link rel="stylesheet" href="{{ asset('lib/sceditor/themes/default.min.css') }}">
<script src="{{ asset('lib/sceditor/sceditor.min.js') }}"></script>
<script src="{{ asset('lib/sceditor/languages/' . App::getLocale() . '.js') }}"></script>
<script src="{{ asset('lib/sceditor/bbcode.js') }}"></script>
<script>$(() => { initSceditor(); })</script>
