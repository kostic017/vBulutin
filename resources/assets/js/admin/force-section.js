$(function() {
    const cParentId = $("select[name=parent_id]");
    const cSectionsId = $("select[name=section_id]");

    const r = route("ajax.getParentSection");

    cParentId.on("change", function () {
        if ($(this).val() === "") {
            cSectionsId.removeAttr("disabled");
            cSectionsId.val($("option:first-child", cSectionsId).val());
        } else {
            $.post(r, { id: $(this).val() },
                function (data) {
                    console.log(data.section_id);
                    cSectionsId.val(data.section_id);
                    cSectionsId.attr("disabled", "");
                }
            ).fail(function (err) {
                alert(err);
            });
        }
    });
});
