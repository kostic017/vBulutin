<?php if (isset($_SESSION["user_id"]) && isset($_SESSION["lastVisitDT"])): ?>
    <?php $topics = qGetTopicsFromLastVisit($_SESSION["lastVisitDT"], LIMIT_LAST_VISIT); ?>

    <section class="sidebar-newtopics">

        <h2 data-shclass="sidebar-title" class="title">Nove teme</h2>

        <div data-shclass="sidebar-content" class="content">

            <?php if ($topics): ?>

                <?php foreach ($topics as $topic): ?>
                    <?php $username = qGetTopicStarterUsername($topic["id"]); ?>

                    <div class="post-info">
                        <a href=""><img src="/public/images/avatars/default.png" alt=""></a>
                        <ul>
                            <?php if ($username): ?>
                                <li><a href=""><?=$username?></a></li>
                            <?php endif; ?>
                            <li><a href="topic.php?id=<?=$topic["id"]?>"><?php echoShorten($topic["title"]); ?></a></li>
                            <li><?=convertMysqlDatetimeToPhpDatetime($topic["startedDT"])?></li>
                        </ul>
                    </div>

                <?php endforeach; ?>

                <a href="newtopics.php">Sve teme od tvoje poslednje posete.</a>

            <?php else: ?>

                <p>Nema tema od tvoje poslednje posete.</p>

            <?php endif; ?>

        </div>

    </section>


<?php endif; ?>
