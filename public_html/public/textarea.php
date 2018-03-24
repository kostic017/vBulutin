<?php
    if (isset($_POST["textarea-submit"])) {
        if (FILENAME === "forum") {
            $topicId = qCreateNewTopic($thisPageId, $_SESSION["user_id"],
                $_POST["textarea-title"], $_POST["textarea-content"]);
            redirectTo("topic.php?id={$topicId}");
        } else {
            qCreateNewPost($thisPageId, $_SESSION["user_id"], $_POST["textarea-content"]);
        }
        redirectTo($_SERVER["REQUEST_URI"]);
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
                <button type="submit" name="textarea-submit">
                    <?=FILENAME === "topic" ? "Pošalji odgovor" : "Nova tema"?>
                </button>
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
            inscrybmde.clearAutosavedValue();

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
</script>
