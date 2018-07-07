$(function () {
    $(".sort-link").on("click", function (event) {
        event.preventDefault();

        const th = $(this).parent();
        if (th.is("[data-order]")) {
            th.attr("data-order", th.attr("data-order") === "asc" ? "desc" : "asc");
        } else {
            $("th").removeAttr("data-order");
            th.attr("data-order", "asc");
        }

        const table = $(this).closest("table");
        const form = $("#" + table.attr("data-formid"));
        const thSort = $("th[data-order]", table);

        $("input[name=sort_column]", form).val(thSort.attr("data-column"));
        $("input[name=sort_order]", form).val(thSort.attr("data-order"));
        form.submit();
    });
})
