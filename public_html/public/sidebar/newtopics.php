<?php if (isset($_SESSION["userId"]) && isset($_SESSION["lastVisitDT"])): ?>
    <?php $topics = qGetTopicsFromLastVisit($_SESSION["lastVisitDT"], LIMIT_LAST_VISIT); ?>

    <section class="sidebar-newtopics">

        <h2 data-shclass="sidebar-title" class="title">Nove teme</h2>

        <div data-shclass="sidebar-content" class="content">

            <?php if ($topics): ?>

                <?php foreach ($topics as $topic): ?>
                    <?php $topicStarter = qGetTopicStarter($topic["id"]); ?>

                    <div class="post-info">
                        <a href="profile.php?id=<?=$topicStarter["id"]?>">
                            <?php displayAvatar($topicStarter, "avatar-small"); ?>
                        </a>
                        <ul>
                            <li><a href=""><?=$topicStarter["username"]?></a></li>
                            <li><a href="topic.php?id=<?=$topic["id"]?>"><?php echoShorten($topic["title"]); ?></a></li>
                            <li><?=convertMysqlDatetimeToPhpDatetime($topic["startedDT"])?></li>
                        </ul>
                    </div>

                <?php endforeach; ?>

                <a href="newtopics.php">Sve nove teme od tvoje poslednje posete.</a>

            <?php else: ?>

                <p>Nema novih tema od tvoje poslednje posete.</p>

            <?php endif; ?>

        </div>

    </section>


<?php endif; ?>
