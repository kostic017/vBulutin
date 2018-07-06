$(function() {
    $(".dd").nestable({
        maxDepth: 2,
        scroll: false
    });

    $('.dd').on('change', function () {
        /* on change event */
    });

    $(".sortable-categories").sortable({
        scroll: false,
        handle: ".category-header",
        connectWith: ".sortable-categories"
    });

    $(".collapse-forums").on("click", function () {
        const dd = $(this).closest(".category-header").siblings(".dd");
        dd.nestable($(this).is(".minus") ? "collapseAll" : "expandAll");
    });

    $(".collapse-categories").on("click", function () {
        const dds = $(".dd");
        const btns = $(".collapse-category");

        if ($(this).is(".minus")) {
            dds.hide();
            console.log("hidden");
            btns.attr("data-action", "");
        } else {
            dds.show();
            btns.attr("data-action", "collapse");
        }
    });

    $(".collapse-category").on("click", function () {
        const dds = $(this).parents(".category-header").siblings(".dd");
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
        const message = $("#message");

        let position = 1;
        $(".dd").each(function () {
            data[$(this).data("categoryid")] = {
                position: position++,
                forums: $(this).nestable("serialize")
            };
        });

        const overlay = $("#overlay");
        overlay.show();
        overlay.fitText();

        $.post('/ajax/forums/positions/save ', { data }, function () {
            location.reload(true);
        }).fail(function () {
            toastr.error($("span[data-key='generic.error']").text());
            overlay.hide();
        });
    });
});
