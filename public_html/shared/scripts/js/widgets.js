function setupWidgets() {

    // Nezeljena ponasanja widget-a
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

    // Sortable
    $(".jui-sortable").sortable({
        items: ".sortable-item",
        placeholder: "ui-state-highlight"
    });

    // Spinner
    $(".spinner-unit")
        .append($("<input class='jui-spinner'>"))
        .append($("<select class='jui-selectmenu'></select>"));
    $(".spinner-unit.in-sheditor .jui-selectmenu").addClass("in-sheditor");

    $(".jui-spinner")
        .spinner({
            min: 1,
            max: 99
        })
        .on("focus", function() {
            $(this).blur(); // onemoguci da korisnik rucno unosi vrednosti u textbox
        });

    $(".jui-spinner[disabled]").spinner("disable");

    // Controlgroup
    $(".jui-controlgroup").controlgroup();

    // Selectmenu
    $(".jui-selectmenu.placeholder").append($("<option value='' disabled selected>-</option>"));
    $(".jui-selectmenu:not(.in-sheditor)").selectmenu();

    // Colorpicker
    $(".jui-colorpicker").colorpicker({
        showOn: "button"
    });

    $(".evo-pointer").on("click", function() {
        const offset = $(this).offset();
        $(this).siblings(".evo-pop")
            .appendTo("[aria-describedby='sheditor']") // vezi popup za dijalog wrapper (umesto za .evo-cp-wrap)
            .offset({
                top: offset.top,
                left: offset.left
            })
            .css("bottom", "auto");
    });

    // Accordion
    $(".jui-accordion").accordion({
        active: false,
        collapsible: true,
        heightStyle: "content"
    });

    $(".jui-accordion.in-sheditor").accordion("disable");

    // Fieldset Toogle
    const fieldsetToogle = $(".fieldset-toggle");
    $(".arrow-icon", fieldsetToogle).addClass("ui-icon ui-icon-triangle-1-n");

    fieldsetToogle
        .addClass("_pointer")
        .click(function() {
            $(".arrow-icon", this).toggleClass("ui-icon-triangle-1-n").toggleClass("ui-icon-triangle-1-s");
            $(this).siblings("div").toggle($(this).hasClass("in-sheditor") ? "" : "blind");
        });

    $(".fieldset-toggle.start-collapsed").click();

    // Selectable
    const juiSelectables = $(".jui-selectable");
    juiSelectables.bind("mousedown", e => {
        e.metaKey = true; // omoguci visestruku selekciju bez da se drzi ctrl
    });

    $(".jui-selectable.disable-multi").selectable({
        selected: (event, ui) => {
            $(ui.selected).addClass("ui-selected").siblings().removeClass("ui-selected");
        }
    });

    $(".jui-selectable:not(.disable-multi)").selectable({
        selected: (event, ui) => {
            // deselektuj sve elemente ako se odabere "none" || deselektuj "none" ako se selektuje neki drugi element
            $(ui.selected).siblings($(ui.selected).hasClass("jui-none") ? "" : ".jui-none").removeClass("ui-selected");
        }
    });

    juiSelectables.selectable("option", "tolerance", "fit"); // onemoguci lasso selekciju
    $("li", juiSelectables).addClass("ui-button");
}
