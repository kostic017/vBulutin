let jsonProperties;

$(function() {

    $.getJSON("/public/schemes/gray/config.json", json => {
        jsonProperties = json;
        fileDoneLoading();
    });

});

function fileDoneLoading() {
    setupWidgets();
    setupEditorPreferences();
    addOptionsToControls();
    setupEditorDialog();
    setupShclassSelectmenu();
    setToogleForBackgroundEndColor();
    liveUpdateElementStyles();
}

function setupEditorPreferences() {
    const outlineWidth = 3;
    const outlineColor = "#9acd32";

    const overlayOpacity = 80;
    const overlayColor = "#984020";

    $("#sh-overlay-opacity").slider({
        value: overlayOpacity,
        slide(event, ui) {
            $(".ui-widget-overlay").css("opacity", ui.value / 100.0);
        }
    });

    $("#sh-overlay-color")
        .colorpicker("val", overlayColor)
        .change(function() {
            $(".ui-widget-overlay").css("background", $(this).val());
        });

    $("#sh-outline-width")
        .spinner("value", outlineWidth)
        .on("spin", function(event, ui) {
            $(".focused").css("outline-width", ui.value + "px");
        });

    $("#sh-outline-color")
        .colorpicker("val", outlineColor)
        .change(function() {
            $(".focused").css("outline-color", $(this).val());
        });
}

function addOptionsToControls() {

    $(".spinner-unit select.jui-selectmenu").each(function() {
        for (let unit of ["%", "cm", "em", "ex", "in", "mm", "pc", "pt", "px", "vh", "vw", "vmin"]) {
            $(this).append($(`<option value="${unit}">${unit}</option>`));
        }
    });

    for (let side of ["-", "-top-", "-right-", "-bottom-", "-left-"]) {
        appendOptionsToSelectMenu(
            `border${side}style`,
            ["none", "hidden", "dotted", "dashed", "solid", "double", "groove", "ridge", "inset", "outset"]
        );
    }

    // ************************************************************************** //
    //                              Pomocne funkcije                              //
    // ************************************************************************** //

    function appendOptionsToSelectMenu(propertyName, options) {
        const selectmenu = $(`div[data-property='${propertyName}'] select.jui-selectmenu`);
        for (let val of options) {
            selectmenu.append($(`<option value="${val}">${val}</option>`));
        }
    }

    // function appendOptionsToSelectable(propertyName, options) {
    //     const selectable = $(`div[data-property='${propertyName}'] ol.jui-selectable`);
    //     for (let val of options) {
    //         selectable.append($(`<li class="${val === "none" ? "jui-none" : ""}">${val}</li>`));
    //     }
    // }
}

function setupEditorDialog() {

    $("#sheditor")
        .dialog({
            width: 600,
            height: 400,
            modal: true,
            autoOpen: false,
            open() {
                $(".ui-widget-overlay").css({
                    opacity: $("#sh-overlay-opacity").slider("value") / 100.0,
                    background: $("#sh-overlay-color").val()
                });
                // Dijalog prekrije Selectmenu drop-down ako se selectmenu() pozove pre nego
                // sto se dijalog otvori jer se drow-down onda generise van dijalog wrappera.
                $(".jui-selectmenu.in-sheditor").selectmenu();
                // Delimicno onemucava skrolovanje stranice dok je dijalog otvoren. Skrolovanje koje
                // se desava kada vucemo dijalog ka ivici prozora resavamo pomocu Widget Factory.
                $("body").css("overflow", "hidden");
            },
            beforeClose() {
                $("body").css("overflow", "inherit");
            },
            close() {
                unFocusElements();
                $(".jui-selectmenu", "#target-select").val("").selectmenu("refresh"); // placeholder
                $(".jui-accordion.in-sheditor").accordion("option", "disabled", true);
            }
        })
        .scroll(() => {
            // Drop-down za Selecmenu je apsolutno pozicioniran tako da ne prati skrolovanje dijaloga
            // nego ostaje zakljucan u mestu i uvek vidljiv. Analogno vazi i za Colorpicker.
            $(".jui-selectmenu.in-sheditor").selectmenu("close");
            $(".jui-colorpicker").colorpicker("hidePalette");
        });

    $("#btn-open-editor").click(() => {
        $("#sheditor").dialog("open");
    });
}

function setupShclassSelectmenu() {
    const targetSelect = $("#target-select");
    const cShclassSelectmenu = $(".jui-selectmenu", targetSelect);

    // dodaj shklase meniju
    $("[data-shclass]").each((i, elem) => {
        for (let shclass of $(elem).attr("data-shclass").split(" ")) {
            if (!doesOptionExist(cShclassSelectmenu[0], shclass)) {
                cShclassSelectmenu.append($(`<option value="${shclass}">${shclass}</option>`));
            }
        }
    });

    cShclassSelectmenu.on("selectmenuchange", (event, ui) => {
        const shclass = ui.item.value;
        revertControlsToNullValues();
        if (shclass === "") {
            $(".jui-accordion.in-sheditor").accordion("disable");
        } else {
            $(".jui-accordion.in-sheditor").accordion("enable");
            focusElementsToStyle(shclass);
            readJsonValues(shclass);
        }
    });

    // ************************************************************************** //
    //                              Pomocne funkcije                              //
    // ************************************************************************** //

    function revertControlsToNullValues() {
        const shAccordion = $(".jui-accordion.in-sheditor");
        $(".jui-selectmenu:not(#target-selectmenu)", shAccordion).val("").selectmenu("refresh");
        $(".jui-selectable li", shAccordion).removeClass("selected");
        $(".jui-colopicker", shAccordion).colorpicker("val", "");
        $(".jui-spinner", shAccordion).spinner("value", "");
    }

    function focusElementsToStyle(shclass) {
        const targets = $(`[data-shclass~=${shclass}]`);
        unFocusElements();
        targets.addClass("focused");
        $(".focused").css({
            "outline-style": "solid",
            "outline-color": $("#sh-outline-color").val(),
            "outline-width": $("#sh-outline-width").spinner("value")
        });
        animateScroll(
            shclass !== "btn-back2top"           // btn-back2top je vidljivo samo ako
                ? $(targets[0]).offset().top     // skrolujemo stranicu na dole.
                : $("body").prop("scrollHeight")
        );
    }

    function readJsonValues(shclass) {
        const properties = jsonProperties[shclass];
        //if (properties === undefined) return;

        for (let propertyName in properties) {
            if (!properties.hasOwnProperty(propertyName) || "Link Background".includes(propertyName))
                continue;
            if (propertyName === "font-family") continue; // TODO

            const propertyValue = properties[propertyName];
            const propertyControl = $(".jui-selectmenu, .jui-colorpicker, .jui-selectable, .spinner-unit",
                `div[data-property='${propertyName}']`);

            if (propertyControl.hasClass("spinner-unit")) {
                const unitAndValue = splitUnitAndValue(propertyValue);
                $(".jui-spinner", propertyControl).spinner("value", unitAndValue.value);
                $(".jui-selectmenu", propertyControl).val(unitAndValue.unit).selectmenu("refresh");
            } else if (propertyControl.hasClass("jui-selectmenu")) {
                propertyControl.val(propertyValue).selectmenu("refresh");
            } else if (propertyControl.hasClass("jui-selectable")) {
                selectSelectableOption(propertyControl, propertyValue); // TODO is this ok?
            } else if (propertyControl.hasClass("jui-colorpicker")) {
                propertyControl.colorpicker("val", propertyValue);
            }
        }

        if (properties.hasOwnProperty("Background")) {
            const {style, "start-color":startColor, "end-color":endColor} = properties["Background"];
            $("div[data-property='background-style'] .jui-selectmenu").val(style).selectmenu("refresh");
            $("div[data-property='background-start-color'] .jui-colorpicker").colorpicker("val", startColor);
            $("div[data-property='background-end-color'] .jui-colorpicker").colorpicker("val", endColor);
        }
    }

}

function setToogleForBackgroundEndColor() {
    $("div[data-property='background-style'] .jui-selectmenu").on("selectmenuchange", (event, ui) => {
        const value = ui.item.value;
        const endColorDiv = $("div[data-property='background-end-color']");
        value.includes("gradient") ? endColorDiv.show() : endColorDiv.hide();
    });
}

function liveUpdateElementStyles() {
    $(".jui-selectmenu.in-sheditor").on("selectmenuchange", function() {
        const targets = $(".focused");
        const parent = $(this).parent();

        let propertyName, propertyValue;

        if (parent[0].hasAttribute("data-property")) {
            propertyName = parent.attr("data-property");
            propertyValue = $(this).val();
        } else if (parent.hasClass("spinner-unit")) {
            propertyName = parent.parent().attr("data-property");
            propertyValue = $(".jui-spinner", parent).spinner("value")
                + $(this).val();
        }

        if (!(propertyName === undefined || propertyValue.includes("-"))) {
            targets.css(propertyName, propertyValue);
        }

    });

}

/////////////////////////////////////////////////////////////////////////////
// Pomocne funkcije
/////////////////////////////////////////////////////////////////////////////

function selectSelectableOption(selectable, values) {
    for (let option of selectable.children()) {
        values.contains(option.innerText) ? option.addClass("ui-selected") : option.removeClass("ui-selected");
    }
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

function unFocusElements() {
    $(".focused").removeClass("focused").css("outline", "none");
}

function animateScroll(position, time = 500) {
    $("html, body").animate({scrollTop: position}, time);
}

function doesOptionExist(dropdown, val) {
    return [...dropdown.options].find(option => option.value === val) !== undefined;
}
