<?php
    if (isset($_POST["textarea-submit"]) && isNotBlank($_POST["textarea-content"])) {

        switch (FILENAME) {

            case "forum":
                $topicId = qCreateNewTopic($thisPageId, $_SESSION["user_id"], $_POST["textarea-title"], $_POST["textarea-content"]);
                redirectTo("topic.php?id={$topicId}");
            break;

            case "topic":
                $lastPost = qGetTopicLastPoster($thisPageId);
                if ($lastPost["user"]["id"] === $_SESSION["user_id"]) {
                    qAppendToPost($lastPost["postId"], $_SESSION["user_id"], $_POST["textarea-content"]);
                    redirectTo("topic.php?id={$thisPageId}#post-{$lastPost["postId"]}");
                } else {
                    $post = qCreateNewPost($thisPageId, $_SESSION["user_id"], $_POST["textarea-content"]);
                    redirectTo("topic.php?id={$thisPageId}#post-{$post["id"]}");
                }
            break;

        }

    }
?>

<?php if (isset($_SESSION["user_id"])): ?>
    <section class="textarea">
        <form action="" method="post" class="new-post-form">
            <?php if (FILENAME === "forum"): ?>
                <div class="textarea-title-row">
                    <label for="textarea-title">Naslov:</label>
                    <input type="text" id="textarea-title" name="textarea-title" required>
                </div>
            <?php endif; ?>
            <textarea name="textarea-content"></textarea>
            <div class="textarea-submit-row">
                <button data-shclass="btn-reply" type="submit" name="textarea-submit"></button>
                <input type="text" id="emojionearea">
            </div>
        </form>
    </section>
<?php endif; ?>

<script>
    $(function () {
        const emojionearea = $("#emojionearea");
        const textarea = $("textarea[name=textarea-content]");

        if (textarea.length > 0 && emojionearea.length > 0) {

            let inscrybmde = new InscrybMDE({
                element: textarea[0],
                spellChecker: false,
                indentWithTabs: false,
                autosave: {
                    delay: 1000,
                    enabled: true,
                    uniqueId: "MyUniqueID",
                },
                toolbar: [
                    "bold", "italic", "strikethrough", "|",
                    "heading-1", "heading-2", "heading-3", "|",
                    "quote", "code", "unordered-list", "ordered-list", "|",
                    "link", "image", "table", "horizontal-rule", "|",
                    "clean-block", "preview", "side-by-side", "fullscreen", "|",
                    "guide"
                ]
            });

            inscrybmde.value("");
            toggleSubmit(inscrybmde);
            inscrybmde.clearAutosavedValue();
            inscrybmde.codemirror.on("change", () => { toggleSubmit(inscrybmde) });

            emojionearea.emojioneArea({
                saveEmojisAs: "shortname",
                events: {
                    emojibtn_click: function (button, event) {
                        inscrybmde.value(inscrybmde.value() + button[0].dataset.name);
                    },
                    focus: function (editor, event) {
                        $("i[data-name=':flag_xk:'").remove(); // ukloni zastavu Kosova
                    }
                }
            });
        }
    });

    function toggleSubmit(inscrybmde) {
        const submitButton = $("button[name=textarea-submit]");
        if (inscrybmde.value().length >= <?=POST_MIN_LENGTH?>) {
            submitButton.removeAttr("title");
            submitButton.removeAttr("disabled");
            submitButton.html("<?=FILENAME?>" === "topic" ? "Pošalji odgovor" : "Otvori novu temu");
        } else {
            submitButton.attr("disabled", "");
            submitButton.html("Poruka prekratka!");
            submitButton.attr("title", "Poruka mora sadržati najmanje <?=POST_MIN_LENGTH?> karaktera.");
        }
    }
</script>
