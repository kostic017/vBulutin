function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function is_not_empty(string) {
    return string.trim().length > 0;
}

function is_equal_to_any_word(haystack, needle, ignoreCase = true) {
    if (ignoreCase)
        needle = needle.toLowerCase();
    for (let word of haystack.split(" ")) {
        if (ignoreCase)
            word = word.toLowerCase();
        if (word === needle)
            return true;
    }
    return false;
}
