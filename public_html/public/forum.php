<?php
    require_once "header.php";

    if (($id = $_GET["id"] ?? "") === "") {
        redirectTo("/public/");
    }
    
    $topics = qGetTopicsByForumId($id);
?>

<main>

    <?php include "topbox.php"; ?>

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
                            <li><a href=""><?=qGetTopicStarterUsername($topic["firstpost_id"])?></a></li>
                            <li><?=$topic["started"]?></li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

    <?php include "permissions.php"; ?>

</main>

<?php include "footer.php"; ?>
