$(function () {
    const table = $("table").data("name");

    $("select[name=perPage], input[name=filter]").on("change", function () {
        $(this).parents("form").submit();
    });

    $(".sort-link").on("click", function () {

        const icon = $(this).children(".sort-icon");
        if (icon.hasClass("ic_s_asc") || icon.hasClass("ic_s_desc")) {
            icon.toggleClass("ic_s_asc").toggleClass("ic_s_desc");
        } else {
            $(".sort-icon").removeClass("ic_s_asc ic_s_desc");
            icon.addClass("ic_s_asc");
        }

        const r = route("ajax.sort", {
            table,
            column: $(this).data("column"),
            order: icon.hasClass("ic_s_asc") ? "asc" : "desc"
        });

        const overlay = $("#overlay");
        overlay.removeClass('d-none');
        overlay.fitText();

        $.get(r, function (data) {
            const table = $("table");
            $.each(data, function () {
                // when you append element that already
                // exists it just moves to a new location
                table.append($(`#row-${this}`));
            });
            overlay.addClass('d-none');
        });

    });
});
