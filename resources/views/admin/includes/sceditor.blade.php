@php ($locale = App::getLocale())
<script src="{{ asset('lib/sceditor/jquery.sceditor.min.js') }}"></script>
<script src="{{ asset("lib/sceditor/languages/{$locale}.js") }}"></script>
<script src="{{ asset('lib/sceditor/jquery.sceditor.bbcode.min.js') }}"></script>
<script>
    const textarea = $('.sceditor')[0];
    sceditor.create(textarea, {
        width: '100%',
        height: '300px',
    	format: 'bbcode',
        bbcodeTrim: true,
        spellcheck: false,
        resizeWidth: false,
        locale: '{{ $locale }}',
        resizeMinHeight: '120px',
        resizeMaxHeight: '1000px',
        emoticonsRoot: '/lib/sceditor/',
        plugins: 'undo',
        pastetext: {
            enabled: true,
            addButton: true
        },
    	style: '{{ asset('lib/sceditor/themes/content/default.min.css') }} '
    });
</script>
