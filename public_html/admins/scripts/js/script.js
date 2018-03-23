$(function () {
    $("button.icon-clear, button.icon-delete").on("click", () => confirm("Sigurno želite da izvršite brisanje?"));

    setupWidgets();
    setupPositionsSaving();
    setupSortingAction();
    forcingParentsSectionToChildren();
    disableFormSubmitOnEnterKeyPress();

    $(`a[href="${getFileName()}"]`).addClass("active");
});

function disableFormSubmitOnEnterKeyPress() {
    $("form input").keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
}

// ==== TABLES ====

function setupSortingAction() {
    const overlay = $("#overlay");
    $("table[data-name] th:nth-child(2) .icon-sort").addClass("icon-ascending");

    $(".btn-sort").on("click", function () {
        let order;
        const sortIcon = $(this).siblings(".icon-sort");
        const tableName = $(this).parents("table").data("name");

        $(this).parents("tbody")
            .children("tr:nth-child(n + 3)")
            .children("td")
            .remove();

        if (sortIcon.hasClass("icon-ascending") || sortIcon.hasClass("icon-descending")) {
            sortIcon.toggleClass("icon-ascending").toggleClass("icon-descending");
            order = sortIcon.hasClass("icon-ascending") ? "ASC" :
                sortIcon.hasClass("icon-descending") ? "DESC" : "ASC";
        } else {
            order = "ASC";
            $(this).parents("tr").find(".icon-sort").removeClass("icon-ascending icon-descending");
            sortIcon.addClass("icon-ascending");
        }

        overlay.show(); // Da ne moze korisnik da spamuje po dugmetu.
        $.post("scripts/php/ajax.php",
            {
                order,
                tableName,
                job: "sort",
                columnName: $(this).text()
            },
            (data) => {
                $(this).parents("tbody").append(data);
                overlay.hide();
            }
        );

    });
}

function forcingParentsSectionToChildren() {
    const cParentId = $("select[name=parentid]");
    const cSectionsId = $("select[name=sections_id]");

    cParentId.on("change", function () {
        if ($(this).val() === "") {
            cSectionsId.prop("readonly", false);
            cSectionsId.val($("option:first-child", cSectionsId).val());
        } else {
            $.post("scripts/php/ajax.php",
                {
                    id: $(this).val(),
                    job: "parent_section"
                },
                function (sectionId) {
                    cSectionsId.val(sectionId);
                    cSectionsId.prop("readonly", true);
                }
            );
        }
    });
}

function updateRowAction(tableName, id) {
    const table = $(`table[data-name="${tableName}"]`);

    const insertRow = $("tr.insert-row", table);
    const insertButtonCell = $("td:first-child", insertRow);
    const insertRowDataCells = $("td:not(:first-child)", insertRow);
    const dataRowDataCells = $(`tr[data-id="${id}"] td:not(:first-child)`, table);

    // Pozicija, roditelj i sekcija se menjaju samo preko positioning-sf.php.
    $("select[name=parentid], select[name=sections_id]", insertRowDataCells).selectmenu("disable");

    let index = 0;
    // Polja za unos popunim postojecim podacima posto se radi o njihovom editovanju.
    insertRowDataCells.each(function () {
        const control = $("> *:first-child", this);
        const data = dataRowDataCells.eq(index++).data("value");

        if (control.is("input[type=checkbox]")) {
            if (data === 0) {
                control.removeAttr("checked");
            } else {
                control.attr("checked", "");
            }
        } else {
            control.val(data);
        }
    });

    // Cancel dugme je tipa submit, pa ce klik na njega osveziti stranicu i
    // sve ce se vratiti na staro sto znaci da nista ne mora da se hendluje posebno.
    insertButtonCell.html(`
        <button type="submit" class="icon icon-okay" name="${tableName}_update" value="${id}"></button>
        <button type="submit" class="icon icon-cancel"></button>
    `);
}

// ==== POSITIONING ====

function setupWidgets() {
    $(".dd").nestable({
        maxDepth: 2,
        scroll: false
    });

    $(".sortable-sections").sortable({
        scroll: false,
        handle: ".section-header",
        connectWith: ".sortable-sections"
    });

    $(".forums-tree-controls").on("click", function (e) {
        const action = $(e.target).text();
        if (isEqualToAnyWord("- +", action)) {
            const dd = $(this).parents(".section-header").siblings(".dd"); // zanima nas odredjena sekcija
            if (action === "+") {
                dd.nestable("expandAll");
            } else if (action === "-") {
                dd.nestable("collapseAll");
            }
        }
    });

    $(".sections-tree-controls").on("click", function (e) {
        const action = $(e.target).text();
        if (isEqualToAnyWord("- +", action)) {
            const dds = $(".dd"); // zanimaju nas sve sekcije
            const btns = $(".section-tree-control");
            if (action === "-") {
                dds.hide();
                btns.attr("data-action", "");
            } else if (action === "+") {
                dds.show();
                btns.attr("data-action", "collapse");
            }
        }
    });

    $(".section-tree-control").on("click", function () {
        const dds = $(this).parents(".section-header").siblings(".dd");
        if ($(this).attr("data-action") === "collapse") {
            dds.hide();
            $(this).attr("data-action", "");
        } else {
            dds.show();
            $(this).attr("data-action", "collapse");
        }
    });
}

function setupPositionsSaving() {
    $("button[name=save]").on("click", function () {
        const data = {};
        $(".dd").each(function () {
            data[$(this).data("sectionid")] = $(this).nestable("serialize");
        });
        $.post("scripts/php/ajax.php",
            {
                data,
                job: "save_positions",
            },
            (returnMessage) => {
                const message = hasSubstring(returnMessage, "error") ?
                    "Došlo je do greške prilikom snimanja." :
                    "Snimanje uspešno izvršeno.";
                $("#message").html(`${message}<br><br>`);
                setTimeout(function () {
                    $("#message").html("");
                }, 3000);
            }
        );
    });
}

