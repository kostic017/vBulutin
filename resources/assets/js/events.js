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

    $(".editpost").on("click", function (event) {
        event.preventDefault();

    });

    $(".deletepost").on("click", function(event) {
        event.preventDefault();

    });

    $(".quotepost").on("click", function(event) {
        event.preventDefault();

        const postId = $(this).attr("data-postid");
        const editor = sceditor.instance(document.querySelector("#sceditor"));

        const overlay = $("#overlay");
        overlay.show();
        overlay.fitText();

        $.post('/ajax/quote', { postId }, function (code) {
            editor.insert(code);
            overlay.hide();
        }).fail(function() {
            toastr.error($("span[data-key='generic.error']").text());
            overlay.hide();
        });

    });
});
