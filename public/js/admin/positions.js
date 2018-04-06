$(function () {
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
            // we are interested in a particular section
            const dd = $(this).parents(".section-header").siblings(".dd");
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
            // we are interested in all sections
            const dds = $(".dd");
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

    $("button[name=save]").on("click", function () {
        const data = {};

        let position = 1;
        $(".dd").each(function () {
            data[$(this).data("sectionid")] = {
                position: position++,
                forums: $(this).nestable("serialize")
            };
        });

        $.post(route("ajax.save.positions"), { data }, function () {
            toastr.success(i18n.toastr.success);
        }).fail(function () {
            toastr.error(i18n.toastr.error);
        });
    });
});
