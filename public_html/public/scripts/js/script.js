let jsonProperties;

$(function () {
    // Back2Top dugme
    const back2top = $("#btn-back2top");
    $(window).scroll(() => { $(window).scrollTop() > 200 ? back2top.fadeIn() : back2top.fadeOut(); });
    back2top.click(() => { animateScroll(0); });
    $(window).scroll();

    // ako nema itema u sidebar, prosiri tabelu
    if (isEmptyString($("aside").html())) {
        $("body").removeClass("has-sidebar");
    }

    // $.getJSON("/public/schemes/gray/config.json", json => {
    //     jsonProperties = json;
    //     fileDoneLoading();
    // });
});

function fileDoneLoading() {
    const sheditor = $("#sheditor");
    const cShclassSelect = $("#target-select select");

    // ************************************************************************** //
    //                                   Dialog                                   //
    // ************************************************************************** //

    // Menjamo nezeljena ponasanja widget-a.
    $.widget("ui.dialog", $.ui.dialog, {

        close(event) {     // Kada se dijalog zatvori fokusira se dugme za otvaranje dijaloga.
            const openerScrollParent = this.opener.scrollParent();
            const scrollTop = openerScrollParent.scrollTop();
            const scrollLeft = openerScrollParent.scrollLeft();
            this._super(event);
            openerScrollParent.scrollTop(scrollTop).scrollLeft(scrollLeft);
        },

        _makeDraggable() { // Stranica se skroluje kada privucemo dijalog do ivice prozora.
            this.uiDialog.draggable({
                scroll: false,
                containment: "window"
            });
        }

    });

    sheditor.dialog({
        width: 600,
        height: 400,
        modal: true,
        autoOpen: false,
        open() {
            $("body").css("overflow", "hidden");
            $(".fieldset-toggle.start-collapsed").click();
            $(".ui-widget-overlay").css({
                opacity: $("#sh-overlay-opacity").val() / 100.0,
                background: $("#sh-overlay-color").val()
            });
        },
        beforeClose() {
            $("body").css("overflow", "inherit");
        },
        close() {
            unfocusElements();
            cShclassSelect.val("");
            $(".fieldset-toggle.start-collapsed").click();
            $("#accordion")
                .accordion("option", "disabled", true)
                .accordion("option", "active", false);
        }
    });

    sheditor.scroll(() => {
        // Drop-down za Colorpicker je apsolutno pozicioniran tako da ne
        // prati skrolovanje dijaloga nego ostaje zakljucan u mestu i uvek vidljiv.
        $(".jui-colorpicker").colorpicker("hidePalette");
    });

    $("#btn-open-editor").click(() => {
        sheditor.dialog("open");
    });

    // ************************************************************************** //
    //                                Colorpicker                                 //
    // ************************************************************************** //

    $(".jui-colorpicker").colorpicker({
        showOn: "button"
    });

    $(".evo-pointer").on("click", function () {
        const offset = $(this).offset();
        $(this).siblings(".evo-pop")
            .appendTo("[aria-describedby='sheditor']") // vezi popup za dijalog wrapper (umesto za .evo-cp-wrap)
            .offset({
                top: offset.top,
                left: offset.left
            })
            .css("bottom", "auto");
    });

    // ************************************************************************** //
    //                                 Accordion                                  //
    // ************************************************************************** //

    $("#accordion").accordion({
        active: false,
        disabled: true,
        collapsible: true,
        heightStyle: "content"
    });

    // ************************************************************************** //
    //                            Podesavanja editora                             //
    // ************************************************************************** //

    $("#sh-overlay-opacity").on("change", function () {
        $(".ui-widget-overlay").css("opacity", $(this).val() / 100.0);
    });

    $("#sh-outline-width").on("change", function () {
        $(".focused").css("outline-width", $(this).val() + "px");
    });

    $("#sh-overlay-color")
        .colorpicker("val", "#984020")
        .change(function () {
            $(".ui-widget-overlay").css("background", $(this).val());
        });

    $("#sh-outline-color")
        .colorpicker("val", "#9acd32")
        .change(function () {
            $(".focused").css("outline-color", $(this).val());
        });

    // ************************************************************************** //
    //                              Fieldset Toogle                               //
    // ************************************************************************** //

    const fieldsetToogle = $(".fieldset-toggle");
    $(".arrow-icon", fieldsetToogle).addClass("ui-icon ui-icon-triangle-1-n");

    fieldsetToogle.click(function () {
        $(".arrow-icon", this).toggleClass("ui-icon-triangle-1-n").toggleClass("ui-icon-triangle-1-s");
        $(this).siblings("div").toggle("blind");
    });

    // ************************************************************************** //
    //                        Broj sa mernom jedinicom                            //
    // ************************************************************************** //

    $(".number-and-unit").append(`
        <input type="number" min="0" max="100" step="1" value="0">
        <select>
            <option value="%">%</option>
            <option value="cm">cm</option>
            <option value="em">em</option>
            <option value="ex">ex</option>
            <option value="in">in</option>
            <option value="mm">mm</option>
            <option value="pc">pc</option>
            <option value="pt">pt</option>
            <option value="px" selected>px</option>
            <option value="vh">vh</option>
            <option value="vw">vw</option>
            <option value="vmin">vmin</option>
        </select>
    `);

    // ************************************************************************** //
    //                         Dodaj opcije kontrolama                            //
    // ************************************************************************** //

    for (let side of ["-", "-top-", "-right-", "-bottom-", "-left-"]) {
        appendOptionsToSelect(
            `border${side}style`,
            ["none", "hidden", "dotted", "dashed", "solid", "double", "groove", "ridge", "inset", "outset"]
        );
    }

    // ************************************************************************** //
    //                        Select meni za shclass-e                            //
    // ************************************************************************** //

    $("[data-shclass]").each((i, elem) => {
        for (let shclass of $(elem).attr("data-shclass").split(" ")) {
            if (!doesOptionExist(cShclassSelect[0], shclass)) {
                cShclassSelect.append($(`<option value="${shclass}">${shclass}</option>`));
            }
        }
    });

    cShclassSelect.on("change", function () {
        const shclass = $(this).val();
        revertControlsToNullValues();
        if (shclass === "") {
            $("#accordion").accordion("disable");
        } else {
            $("#accordion").accordion("enable");
            focusElementsToStyle(shclass);
            readJsonValues(shclass);
        }
    });

    // ************************************************************************** //
    //                      Toogle Background End Color                           //
    // ************************************************************************** //

    $("div[data-property='background-style'] select").on("change", function() {
        const endColorDiv = $("div[data-property='background-end-color']");
        $(this).val().includes("gradient") ? endColorDiv.show() : endColorDiv.hide();
    });

    // ************************************************************************** //
    //                     Live Update stilova elemenata                          //
    // ************************************************************************** //

    $("#sheditor select").on("change", function () {
        const targets = $(".focused");
        const parent = $(this).parent();

        let propertyName, propertyValue;

        if (parent[0].hasAttribute("data-property")) {
            propertyName = parent.attr("data-property");
            propertyValue = $(this).val();
        } else if (parent.hasClass("number-and-unit")) {
            propertyName = parent.parent().attr("data-property");
            propertyValue = $("input[type=number]", parent).val() + $(this).val();
        }

        if (!(propertyName === undefined || propertyValue.includes("-"))) {
            targets.css(propertyName, propertyValue);
        }

    });
}

function appendOptionsToSelect(propertyName, options) {
    const select = $(`div[data-property='${propertyName}'] select`);
    for (let val of options) {
        select.append($(`<option value="${val}">${val}</option>`));
    }
}

function revertControlsToNullValues() {
    const accordion = $("#accordion");
    $("select", accordion).val("");
    $("input[type=number]", accordion).val("0");
    $("input[type=checkbox], input[type=radio]", accordion).prop("checked", false);
}

function focusElementsToStyle(shclass) {
    const targets = $(`[data-shclass~=${shclass}]`);

    unfocusElements();
    targets.addClass("focused");

    $(".focused").css({
        "outline-style": "solid",
        "outline-width": $("#sh-outline-width").val(),
        "outline-color": $("#sh-outline-color").val()
    });

    animateScroll(
        shclass === "btn-back2top"           // btn-back2top je vidljivo samo ako
            ? $("body").prop("scrollHeight") // skrolujemo stranicu na dole.
            : $(targets[0]).offset().top
    );
}

function unfocusElements() {
    $(".focused").removeClass("focused").css("outline", "none");
}

function readJsonValues(shclass) {
    const properties = jsonProperties[shclass];

    for (let propertyName in properties) {
        if (properties.hasOwnProperty(propertyName) && !"Link Background".includes(propertyName)) {
            const propertyValue = properties[propertyName];
            const propertyControl =
                $("select, input, label, .number-and-unit", $(`div[data-property="${propertyName}"]`));

            if (propertyControl.hasClass("number-and-unit")) {
                const unitAndValue = splitUnitAndValue(propertyValue);
                $("input", propertyControl).val(unitAndValue.value);
                $("select", propertyControl).val(unitAndValue.unit);
            } else if (propertyControl.hasClass("jui-colorpicker")) {
                propertyControl.colorpicker("val", propertyValue);
            } else if (propertyControl.is("select, input")) {
                if (propertyName === "font-family") {
                    const fonts = propertyValue.split(",");
                    $(propertyControl[0]).val(fonts[0].replace(/'/g, ""));
                    $(propertyControl[1]).val(fonts[1]);
                } else {
                    propertyControl.val(propertyValue);
                }
            } else if (propertyControl.is("label")) {
                $(`input[value="${propertyValue}"]`, propertyControl).prop("checked", "true");
            }
        }
    }

    if (properties.hasOwnProperty("Background")) {
        const {style, "start-color": startColor, "end-color": endColor} = properties["Background"];
        $("div[data-property='background-style'] select").val(style);
        $("div[data-property='background-start-color'] .jui-colorpicker").colorpicker("val", startColor);
        $("div[data-property='background-end-color'] .jui-colorpicker").colorpicker("val", endColor);
    }
}



