$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(window).scroll(function () {
        // fiksiram ga samo u horizontalnom smeru
        $(".navbar").css("top", "-" + $(window).scrollTop() + "px");
    });

    $("#toggle-sidebar").on("click", function() {
        $("#sidebar").toggleClass("show").toggleClass("hide");
    });

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

});
