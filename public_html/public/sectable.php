<?php
    $rootForums = qGetForumsBySectionId($section["id"], true, SORT::POSITION_ASCENDING);
?>

<table data-shclass="main-table" class="main-table">

    <caption data-shclass="caption captionbar" class="captionbar">
        <a href="section.php?id=<?=$section["id"]?>"><?=$section["title"]?></a>
    </caption>

    <?php if ($rootForums): ?>

        <?php foreach ($rootForums as $rootForum): ?>
            <?php
                $lastPost = qGetLastPostInfoByForumId($rootForum["id"]);
                $childForums = qGetForumsByParentId($rootForum["id"], SORT::POSITION_ASCENDING);
            ?>

            <tr data-shclass="table-row" class="table-row">
                <td>
                    <span class="icon icon-forum-new"></span>
                    <a data-shclass="row-name" href="forum.php?id=<?=$rootForum["id"]?>"
                        class="name"><?=$rootForum["title"]?></a>
                    <?php if (count($childForums) > 0): ?>
                        <div class="subforums">
                            Potforumi:
                            <ul data-shclass="subforums" class="subforum-list post-list">
                                <?php foreach ($childForums as $childForum): ?>
                                    <li class="icon-post-old">
                                        <a href="forum.php?id=<?=$childForum["id"]?>"><?=$childForum["title"]?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?=qCountTopicsInRootForum($rootForum["id"])?></strong> tema/e<br>
                    <strong><?=qCountPostsInRootForum($rootForum["id"])?></strong> poruka/e
                </td>
                <td>
                    <?php if ($lastPost): ?>
                        <div class="post-info">
                            <a href=""><img src="/public/images/avatars/default.png" alt=""></a>
                            <ul>
                                <li><a href=""><?=$lastPost["username"]?></a></li>
                                <li><?=$lastPost["time"]?></li>
                                <li><?=$lastPost["date"]?></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>

        <?php endforeach; ?>

    <?php else: ?>

        <tr data-shclass="table-row" class="table-row">
            <td>Nema foruma u ovoj sekciji.</td>
        </tr>

    <?php endif; ?>

</table>

<?php /*
    <tr data-shclass="table-row" class="table-row">
        <td>
            <span class="icon icon-forum-redirect"></span>
            <a data-shclass="row-name" href="" class="name">Preusmeravanje, reklame?</a>
        </td>
        <td colspan="2"><strong>0</strong> klika</td>
    </tr>
*/ ?>
