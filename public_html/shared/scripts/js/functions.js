function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function isEmptyString(string){
    return $.trim(string) === "";
}

function isEmptyObject(obj) {
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
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