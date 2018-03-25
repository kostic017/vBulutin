<?php
    require_once "header.php";

    if (!(isset($_SESSION["user_id"]) && isset($_SESSION["lastVisitDT"]))) {
        redirectTo("index.php");
    }

    $topics = qGetTopicsFromLastVisit($_SESSION["lastVisitDT"]);
?>

<main>

    <?php if ($topics): ?>

        <?php foreach ($topics as $topic): ?>
            <?php $username = qGetTopicStarterUsername($topic["id"]); ?>

            <div class="post-info">
                <a href=""><img src="/public/images/avatars/default.png" alt=""></a>
                <ul>
                    <?php if ($username): ?>
                        <li><a href=""><?=$username?></a></li>
                    <?php endif; ?>
                    <li><a href="topic.php?id=<?=$topic["id"]?>"><?=$topic["title"]?></a></li>
                    <li><?=convertMysqlDatetimeToPhpDatetime($topic["startedDT"])?></li>
                </ul>
            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Nema tema od tvoje poslednje posete.</p>

    <?php endif; ?>

</main>

<?php require_once "footer.php"; ?>
