<?php
    include "header.php";
    require_once "../shared/libraries/Parsedown/Parsedown.php";
    require_once "../shared/libraries/emojione/autoload.php";

    if (isset($_POST["new-post"])) {
        qCreateNewPost($id, $_SESSION["user_id"], $_POST["post-content"]);
    }

    $parsedown = new Parsedown();
    $posts = qGetPostsByTopicId($id);
    $emojione = new \Emojione\Client(new \Emojione\Ruleset());
?>

<main>
    <?php include "topbox.php"; ?>

    <div data-shclass="captionbar" class="captionbar">1 reply to this topic</div>

    <section data-shclass="post-container" class="post-container">

        <?php foreach ($posts as $post): ?>
            <?php $user = qGetUserByPostId($post["id"]); ?>

            <div data-shclass="post-box" class="post-box">

                <div data-shclass="post-body" class="post-body">

                    <div data-shclass="post-user" class="post-user">
                        <p class="name"><a href=""><?=$user["username"]?></a></p>
                        <p class="avatar"><a href="">
                            <img src="/public/images/avatars/default.png" alt="<?=$user["username"]?>">
                        </a></p>
                        <p data-shclass="user-title user-admin"><a href="sviadmini"><span>Administrators</span></a></p>
                        <p class="titleimg"><a href="sviadmini"><img src="" alt="Administrator"></a></p>
                        <p class="postcount"><span>5 posts</span></p>
                    </div>

                    <div class="post-main">

                        <div class="content">
                            <small>Napisano <?=convertMysqlDatetimeToPhpDatetime($post["posted"])?></small>
                            <?=$emojione->shortnameToImage($parsedown->text($post["content"]));?>
                        </div>

                        <ul class="buttons">
                            <li><a href="">Prijavi</a></li>
                            <li><a href="">Zakači</a></li>
                            <li><a href="">Izmeni</a></li>
                            <li><a href="">Višestruko citiranje</a></li>
                            <li><a href="">Citiraj</a></li>
                        </ul>
                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </section>

    <?php if (isset($_SESSION["user_id"])): ?>
        <section class="new-post">
            <form action="" method="post">
                <textarea name="post-content"></textarea>
                <div class="new-post-row">
                    <button type="submit" name="new-post">Pošalji odgovor</button>
                    <input type="text" id="emojionearea">
                </div>
            </form>
        </section>
    <?php endif; ?>

    <?php include "permissions.php"; ?>

    <script>
        $(function() {
            const textarea = $("textarea[name=post-content]");
            const emojionearea = $("#emojionearea");

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
                        emojibtn_click: function(button, event) {
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

</main>

<?php include "footer.php"; ?>
