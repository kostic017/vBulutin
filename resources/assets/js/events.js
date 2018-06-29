$(function () {
    $(".sort-link").on("click", function (event) {
        event.preventDefault();

        const th = $(this).parent();
        if (th.is("[data-order]")) {
            th.data("order", th.data("order") === "asc" ? "desc" : "asc");
        } else {
            $("th").removeAttr("data-order");
            th.attr("data-order", "asc");
        }

        const form = $("form#index");
        const thSort = $("th[data-order]");
        appendDataToForm(form, "sort_column", thSort.data("column"));
        appendDataToForm(form, "sort_order", thSort.data("order"));
        form.submit();
    });

    $(".g-recaptcha").on("click", function () {
        $(this).closest("form").attr("id", "submit-me");
    });
});
