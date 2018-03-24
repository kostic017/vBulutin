$(function () {
    setupWidgets();
    setupPositionsSaving();
});

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

        let position = 1;
        $(".dd").each(function () {
            data[$(this).data("sectionid")] = {
                position: position++,
                forums: $(this).nestable("serialize")
            };
        });

        $.post("scripts/php/ajax.php", {
                data,
                job: "positioning_save",
            },
            (returnMessage) => {
                const message = hasSubstring(returnMessage, "error") || hasSubstring(returnMessage, "failed") ?
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

