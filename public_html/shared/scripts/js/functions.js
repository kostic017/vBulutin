function redirectTo(url, timeout = 0) {
    if (timeout === 0) {
        location.replace(url);
    } else {
        setTimeout(function() { location.replace(url); }, timeout);
    }
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
    const results = regex.exec(url);
    if (!results) {
        return null;
    }
    if (!results[2]) {
        return "";
    }
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function isEmptyString(string) {
    return $.trim(string) === "";
}

function isEmptyObject(obj) {
    for (var key in obj) {
        if (obj.hasOwnProperty(key))
            return false;
    }
    return true;
}

function doesOptionExist(dropdown, val) {
    return [...dropdown.options].find(option => option.value === val) !== undefined;
}

function getFileName() {
    var url = document.location.href;
    url = url.substring(0, (url.indexOf("#") == -1) ? url.length : url.indexOf("#"));
    url = url.substring(0, (url.indexOf("?") == -1) ? url.length : url.indexOf("?"));
    url = url.substring(url.lastIndexOf("/") + 1, url.length);
    return url;
}

function isEqualToAnyWord(haystack, needle) {
    for (let string of [...haystack]) {
        if (string === needle) {
            return true;
        }
    }
    return false;
}

function hasSubstring(haystack, needle) {
    return haystack.indexOf(needle) >= 0;
}

function splitUnitAndValue(valWithUnit) {
    let index = 0;
    for (let char of [...valWithUnit]) {
        if (isNaN(char)) {
            return {
                value: valWithUnit.slice(0, index),
                unit: valWithUnit.slice(index)
            };
        }
        ++index;
    }
    return undefined;
}

function animateScroll(position, time = 500) {
    $("html, body").animate({scrollTop: position}, time);
}
