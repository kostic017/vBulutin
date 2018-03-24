$(function () {
    setupSortingAction();
    forcingParentsSectionToChildren();
    disableFormSubmitOnEnterKeyPress();

    $("button.icon-clear, button.icon-delete").on("click", () => confirm("Sigurno želite da izvršite brisanje?"));
});

function disableFormSubmitOnEnterKeyPress() {
    $("form input").keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
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
            $.post("scripts/php/ajax.php", {
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

function setupSortingAction() {
    const overlay = $("#overlay");
    $("table[data-name] th:nth-child(2) .icon-sort").addClass("icon-ascending");

    $(".btn-sort").on("click", function () {
        let order;
        const sortIcon = $(this).siblings(".icon-sort");
        const tableName = $(this).parents("table").data("name");

        console.log(sortIcon);

        $(this).parents("tbody")
            .children("tr:nth-child(n + 3)") // preskoci zaglavlja i insert kontrole
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
        $.post("scripts/php/ajax.php", {
                order,
                tableName,
                job: "table_sort",
                columnName: $(this).text()
            },
            (data) => {
                $(this).parents("tbody").append(data);
                overlay.hide();
            }
        );

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
        <button type="submit" class="icon icon-okay" name="update" value="${tableName}_${id}"></button>
        <button type="submit" class="icon icon-cancel"></button>
    `);
}
