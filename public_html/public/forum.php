<?php
    require_once "header.php";
    require_once "../shared/scripts/php/main.php";

    $topics = qGetTopicsByForumId($thisPageId);
    $childForums = qGetForumsByParentId($thisPageId, SORT::POSITION_ASCENDING);
?>

<main>

    <?php require_once "topbox.php"; ?>

    <?php if (count($childForums) > 0): ?>
        <table data-shclass="main-table" class="main-table">
            <caption data-shclass="caption captionbar" class="captionbar">
                <a href="#">Potforumi</a>
            </caption>

            <?php foreach ($childForums as $childForum): ?>
                <?php $lastPost = qGetLastPostInfoByForumId($childForum["id"]); ?>

                <tr data-shclass="table-row" class="table-row">
                    <?php
                        if (isset($_SESSION["userId"])) {
                            $icon = qDidUserReadForum($_SESSION["userId"], $childForum["id"]) ? "old" : "new";
                        } else {
                            $icon = "none";
                        }
                    ?>
                    <td class="post-<?=$icon?>">
                        <span class="icon"></span>
                        <a data-shclass="row-name" href="forum.php?id=<?=$childForum["id"]?>" class="name">
                            <?=$childForum["title"]?>
                        </a>
                    </td>
                    <td>
                        <strong><?=qCountTopicsInRootForum($childForum["id"])?></strong> tema/e<br>
                        <strong><?=qCountPostsInRootForum($childForum["id"])?></strong> poruka/e
                    </td>
                    <td>
                        <?php if ($lastPost): ?>
                            <div class="post-info">
                                <a href="profile.php?id=<?=$lastPost["user"]["id"]?>">
                                    <?php displayAvatar($lastPost["user"], "avatar-small"); ?>
                                </a>
                                <ul>
                                    <li><a href=""><?=$lastPost["user"]["username"]?></a></li>
                                    <li><?=$lastPost["time"]?></li>
                                    <li><?=$lastPost["date"]?></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <section data-shclass="captionbar" class="sort-bar captionbar">
        <ul>
            <li><a href="" class="active">Skoro ažurirano</a></li>
            <li><a href="">Datum otvaranja</a></li>
            <li><a href="">Najviše odgovora</a></li>
            <li><a href="">Najviše pregleda</a></li>
            <li>
                <ul class="submenu">
                    <li><a href="">Filter TRIANGLE</a></li>
                </ul>
            </li>
        </ul>
    </section>

    <table data-shclass="main-table" class="main-table">
        <?php if ($topics): ?>
            <?php foreach ($topics as $topic): ?>
                <tr data-shclass="table-row" class="table-row">
                    <?php
                        if (isset($_SESSION["userId"])) {
                            $icon = qDidUserReadTopic($_SESSION["userId"], $topic["id"]) ? "old" : "new";
                        } else {
                            $icon = "none";
                        }
                    ?>
                    <td class="post-<?=$icon?>">
                        <span class="icon"></span>
                        <a href="topic.php?id=<?=$topic["id"]?>" class="name"><?=$topic["title"]?></a>
                    </td>
                    <td>
                        <strong><?=qCountPostsInTopic($topic["id"]) - 1?></strong> odgovor<br>
                        <strong>50000</strong> pregleda
                    </td>
                    <td>
                        <div class="post-info">
                            <a href=""><img src="/public/images/avatars/default.png" alt=""></a>
                            <ul>
                                <?php $lastPoster = qGetTopicLastPoster($topic["id"])["user"]; ?>
                                <li><a href=""><?=$lastPoster["username"]?></a></li>
                                <li><?=convertMysqlDatetimeToPhpTime($topic["latestPostDT"])?></li>
                                <li><?=convertMysqlDatetimeToPhpDate($topic["latestPostDT"])?></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr data-shclass="table-row" class="table-row">
                <td>Nema tema u ovom forumu.</td>
            </tr>
        <?php endif; ?>
    </table>

    <?php require_once "textarea.php"; ?>
    <?php require_once "permissions.php"; ?>

</main>

<?php require_once "footer.php"; ?>
