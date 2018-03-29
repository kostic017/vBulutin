<?php
    require_once "header.php";

    if (!(isset($_SESSION["userId"]) && isset($_SESSION["lastVisitDT"]))) {
        redirectTo("index.php");
    }

    $posts = qGetPostsFromLastVisit($_SESSION["lastVisitDT"]);
?>

<main>

    <?php if ($posts): ?>

        <ul class="post-list">

            <?php foreach ($posts as $post): ?>
                <?php
                    $username = qGetUsernameById($post["userId"]);
                    $topicTitle = qGetTopicTitleById($post["topicId"]);

                    if (isset($_SESSION["userId"])) {
                        $icon = qDidUserReadTopic($_SESSION["userId"], $post["topicId"]) ? "old" : "new";
                    } else {
                        $icon = "none";
                    }
                ?>

                <li class="icon-post-<?=$icon?>">
                    <a href="topic.php?id=<?=$post["topicId"]?>&post=<?=$post["id"]?>"><?=($topicTitle)?></a>
                    - <a href=""><?=$username?></a><br>
                    <?=convertMysqlDatetimeToPhpDatetime($post["postedDT"])?>
                </li>
            <?php endforeach; ?>

        </ul>

    <?php else: ?>

        <p>Nema novih poruka od tvoje poslednje posete.</p>

    <?php endif; ?>

</main>

<?php require_once "footer.php"; ?>
