$(function() {
    const cParentId = $("select[name=parent_id]");
    const cSectionsId = $("select[name=category_id]");

    cParentId.on("change", function () {
        if ($(this).val() === "") {
            cSectionsId.removeAttr("disabled");
            cSectionsId.val($("option:first-child", cSectionsId).val());
        } else {
            $.post("/ajax/getParentSection", { id: $(this).val() },
                function (data) {
                    cSectionsId.val(data.category_id);
                    cSectionsId.attr("disabled", "");
                }
            ).fail(function (err) {
                alert(err);
            });
        }
    });
});
