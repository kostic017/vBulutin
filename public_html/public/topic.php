<?php
    require_once "header.php";
    require_once "../shared/libraries/emojione/autoload.php";
    require_once "../shared/libraries/Parsedown/Parsedown.php";

    $parsedown = new Parsedown();
    if ($posts = qGetPostsByTopicId($thisPageId)) {
        $firstPost = reset($posts);
    }

    $emojione = new \Emojione\Client(new \Emojione\Ruleset());
    $emojione->ignoredRegexp .= "|<code[^>]*>[\s\S]*?<\/code>"; // ne parsuj smajlije unutar code tagova
?>

<main>
    <?php require_once "topbox.php"; ?>

    <div data-shclass="captionbar" class="captionbar">
        <?=qCountPostsInTopic($thisPageId) - 1?> odgovor(a) na ovu temu
    </div>

    <section data-shclass="post-container" class="post-container">

        <?php foreach ($posts as $post): ?>
            <?php $user = qGetUserById($post["userId"]); ?>

            <div data-shclass="post-box" class="post-box" id="<?=$post["id"]?>">

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
                        <div>
                            <a href="topic.php?id=<?=$thisPageId?>&post=<?=$post["id"]?>">
                                <small>Napisano <?=convertMysqlDatetimeToPhpDatetime($post["postedDT"])?></small>
                            </a>
                            <div class="content">
                                <?=$emojione->shortnameToImage($parsedown->text($post["content"]));?>
                            </div>
                        </div>

                        <ul class="buttons">
                            <?php if ($post["id"] === $firstPost["id"]): ?>
                                <li><a href="">Zakači</a></li>
                            <?php endif; ?>
                            <li><a href="">Prijavi</a></li>
                            <li><a href="">Izmeni</a></li>
                            <li><a href="javascript:void(0)" class="quote">Citiraj</a></li>
                        </ul>
                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </section>

    <?php require_once "textarea.php"; ?>
    <?php require_once "permissions.php"; ?>

</main>

<script>
    $(function () {
        if (post = getParameterByName("post")) {
            animateScroll($(`#${post}`).position().top);
        }
    });
</script>

<?php require_once "footer.php"; ?>
