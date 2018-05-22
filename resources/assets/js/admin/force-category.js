function forceCategory() {
    $("select[name=parent_id]").on("change", function () {
        const overlay = $("#overlay");
        const cSectionsId = $("select[name=category_id]");

        if ($(this).val() === "") {
            cSectionsId.removeAttr("disabled");
            cSectionsId.val($("option:first-child", cSectionsId).val());
        } else {
            overlay.show();
            $.post("/ajax/getParentCategory", { id: $(this).val() },
                function (data) {
                    cSectionsId.val(data.category_id);
                    cSectionsId.attr("disabled", "");
                    overlay.hide();
                }
            ).fail(function (err) {
                toastr.error($("span[data-key='generic.error']").text());
            });
        }
    });
}
