<?php
    require_once "header.php";
    require_once "../shared/scripts/php/main.php";

    if (($id = $_GET["id"] ?? "") === "") {
        redirectTo("/public/");
    }

    $topics = qGetTopicsByForumId($id);

    $childForums = qGetForumsByParentId($id, SORT::POSITION_ASCENDING);
?>

<main>

    <?php include "topbox.php"; ?>

    <?php if (count($childForums) > 0): ?>
        <table data-shclass="main-table" class="main-table">
            <caption data-shclass="caption captionbar" class="captionbar">
                <a href="#">Potforumi</a>
            </caption>

            <?php foreach ($childForums as $childForum): ?>
                <tr data-shclass="table-row" class="table-row">
                    <td>
                        <span class="icon icon-forum-new"></span>
                        <a data-shclass="row-name" href="forum.php?id=<?=$childForum["id"]?>"
                           class="name"><?=$childForum["title"]?></a>
                        <span class="desc"><?=$childForum["description"]?></span>
                    </td>
                    <td>
                        <strong><?=qCountTopicsInRootForum($childForum["id"])?></strong> tema/e<br>
                        <strong><?=qCountPostsInRootForum($childForum["id"])?></strong> poruka/e
                    </td>
                    <td>
                        <div class="post-info">
                            <a href=""><img src="/public/images/avatars/default.png" alt=""></a>
                            <ul>
                                <li><a href="">Zoki</a></li>
                                <li>14:15</li>
                                <li>23 april 2011.</li>
                            </ul>
                        </div>
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

        <?php foreach ($topics as $topic): ?>
            <tr data-shclass="table-row" class="table-row">
                <td>
                    <span class="icon icon-forum-new"></span>
                    <a href="topic.php" class="name"><?=$topic["title"]?></a>
                    <span class="desc">Prvih nekoliko recenica prvog posta...</span>
                </td>
                <td>
                    <strong><?=qCountPostsInTopic($topic["id"]) - 1?></strong> odgovor<br>
                    <strong>50000</strong> pregleda
                </td>
                <td>
                    <div class="post-info">
                        <a href=""><img src="/public/images/avatars/default.png" alt=""></a>
                        <ul>
                            <li><a href=""><?=qGetTopicLastPosterUsername($topic["id"])?></a></li>
                            <li><?=convertMysqlDatetimeToPhpTime($topic["updated"])?></li>
                            <li><?=convertMysqlDatetimeToPhpDate($topic["updated"])?></li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

    <?php include "permissions.php"; ?>

</main>

<?php include "footer.php"; ?>
