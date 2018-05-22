function forceCategory() {
    $("select[name=parent_id]").on("change", function () {
        const selCategoryId = $("select[name=category_id]");
        if ($(this).val() === "") {
            selCategoryId.removeAttr("disabled");
            selCategoryId.val($("option:first-child", selCategoryId).val());
        } else {
            selCategoryId.attr("disabled", "");
            selCategoryId.val($("select[name=parent_id] option:selected").attr("data-category"));
        }
    });
}
