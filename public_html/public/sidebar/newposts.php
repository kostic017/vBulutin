<?php if (isset($_SESSION["user_id"]) && isset($_SESSION["lastVisitDT"])): ?>
    <?php $posts = qGetPostsFromLastVisit($_SESSION["lastVisitDT"], LIMIT_LAST_VISIT); ?>

    <section class="sidebar-newmessages">

        <h2 data-shclass="sidebar-title" class="title">Nove poruke</h2>

        <div data-shclass="sidebar-content" class="content">

            <?php if ($posts): ?>

                <ul class="post-list">

                    <?php foreach ($posts as $post): ?>
                        <?php
                            $username = qGetUsernameById($post["userId"]);
                            $topicTitle = qGetTopicTitleById($post["topicId"]);
                        ?>

                        <li class="icon-post-new">
                            <a href="topic.php?id=<?=$post["topicId"]?>#post-<?=$post["id"]?>">
                                <?php echoShorten($topicTitle)?>
                            </a> - <a href=""><?=$username?></a>,<br>
                            <?=convertMysqlDatetimeToPhpDatetime($post["postedDT"])?>
                        </li>
                    <?php endforeach; ?>

                </ul>

                <a href="newposts.php">Sve nove poruke od tvoje poslednje posete.</a>

            <?php else: ?>

                <p>Nema novih poruka od tvoje poslednje posete.</p>

            <?php endif; ?>

        </div>

    </section>

<?php endif; ?>
