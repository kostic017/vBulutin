function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function captchaOnSubmit(token) {
    $("#captcha-form").submit();
}

function is_not_empty(string) {
    return string.trim().length > 0;
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

function appendDataToForm(form, name, value) {
    if (is_not_empty(value)) {
        form.append($("<input>").attr("type", "hidden").attr("name", name).val(value));
    }
}
