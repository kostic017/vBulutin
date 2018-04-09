window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) { }

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function isEqualToAnyWord(haystack, needle, ignoreCase = true) {
    if (ignoreCase) {
        needle = needle.toLowerCase();
    }

    for (let word of haystack.split(" ")) {
        if (ignoreCase) {
            word = word.toLowerCase();
        }

        if (word === needle) {
            return true;
        }
    }

    return false;
}

