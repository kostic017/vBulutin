function initSceditor() {
    sceditor.command.set("undobtn", {
        exec: "undo",
        txtExec: function () {
            document.execCommand("undo", false, null);
        }
    });

    sceditor.command.set("redobtn", {
        exec: "redo",
        txtExec: function () {
            document.execCommand("redo", false, null)
        }
    });

    sceditor.create(document.querySelector("#sceditor"), {
        width: "100%",
        height: "400px",
        format: "bbcode",
        bbcodeTrim: true,
        spellcheck: false,
        resizeWidth: false,
        toolbarExclude: "email",
        resizeMinHeight: "200px",
        resizeMaxHeight: "1000px",
        locale: $("html").attr("lang"),
        emoticonsRoot: "/lib/sceditor/emoticons/",
        emoticons: {
            dropdown: {
                ":smile:": "smile.png",
                ":grin:": "grin.png",
                ":laughing:": "laughing.png",
                ":sad:": "sad.png",
                ":pouty:": "pouty.png",
                ":angry:": "angry.png",
                ":blink:": "blink.png",
                ":blush:": "blush.png",
                ":cheerful:": "cheerful.png",
                ":cool:": "cool.png",
                ":cwy:": "cwy.png",
                ":pinch:": "pinch.png",
                ":shocked:": "shocked.png",
                ":sideways:": "sideways.png",
                ":silly:": "silly.png",
                ":sleeping:": "sleeping.png",
                ":tongue:": "tongue.png",
                ":kissing:": "kissing.png",
                ":unsure:": "unsure.png",
                ":w00t:": "w00t.png",
                ":wassat:": "wassat.png",
                ":whistling:": "whistling.png",
                ":dizzy:": "dizzy.png",
                ":ermm:": "ermm.png",
                ":getlost:": "getlost.png",
                ":happy:": "happy.png",
                ":wink:": "wink.png",
                ":face:": "face.png",
                ":angel:": "angel.png",
                ":devil:": "devil.png",
                ":wub:": "wub.png",
                ":sick:": "sick.png",
                ":alien:": "alien.png",
                ":heart:": "heart.png",
                ":ninja:": "ninja.png"
            }
        },
        plugins: "undo",
        toolbar: "bold,italic,underline,strike,subscript,superscript|" +
            "left,center,right,justify|font,size,color,removeformat|" +
            "cut,copy,pastetext|bulletlist,orderedlist,indent,outdent|" +
            "table|bbcodetag,code,quote|horizontalrule,image,email,link,unlink|" +
            "emoticon,youtube,date,time|ltr,rtl|undobtn,redobtn|print,maximize,source",
        pastetext: {
            enabled: true,
            addButton: true
        },
        style: "/lib/sceditor/themes/content/default.min.css"
    });
}
