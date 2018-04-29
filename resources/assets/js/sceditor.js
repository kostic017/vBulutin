function initSceditor() {
    sceditor.create(document.querySelector('.sceditor'), {
        width: "100%",
        height: "300px",
        format: "bbcode",
        bbcodeTrim: true,
        spellcheck: false,
        resizeWidth: false,
        toolbarExclude: 'email',
        resizeMinHeight: "120px",
        resizeMaxHeight: "1000px",
        locale: $("html").attr("lang"),
        emoticonsRoot: "/lib/sceditor/",
        plugins: "undo",
        pastetext: {
            enabled: true,
            addButton: true
        },
        style: "/lib/sceditor/themes/content/default.min.css"
    });
}
