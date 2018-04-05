$(function () {

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr("content")
        }
    });

    $(window).scroll(function () {
        // fiksiram ga samo u horizontalnom smeru
        $(".navbar").css("top", "-" + $(window).scrollTop() + "px");
    });

    $("#toggle-sidebar").on("click", function () {
        $("#sidebar").toggleClass("show").toggleClass("hide");
    });

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    $(".sort-link").on("click", function () {
        const icon = $(this).siblings(".sort-icon");
        if (icon.hasClass("ic_s_asc") || icon.hasClass("ic_s_desc")) {
            icon.toggleClass("ic_s_asc").toggleClass("ic_s_desc");
        } else {
            $(".sort-icon").removeClass("ic_s_asc ic_s_desc");
            icon.addClass("ic_s_asc");
        }

        const r = route("ajax.sort", {
            table: $("h2").text().trim(),
            column: $(this).text().trim(),
            order: icon.hasClass("ic_s_asc") ? "asc" : "desc"
        });

        $.get(r, function (data) {
            const table = $(".horizontal table");
            $.each(data, function () {
                // ako se appenduje element koji vec postoji
                // onda se samo premesti na novu lokaciju
                table.append($(`#row-${this}`));
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Positioning
    |--------------------------------------------------------------------------
    */

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
            toastr.success($(`span[data-lngkey='messages.success']`).text());
        }).fail(function () {
            toastr.error($(`span[data-lngkey='messages.error']`).text());
        });
    });

})
