$(function () {

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
            const table = $("table");
            $.each(data, function () {
                // when you append element that already
                // exists it just moves to a new location
                table.append($(`#row-${this}`));
            });
        });
    });

});
