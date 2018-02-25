<?php
    $section = qGetRowById($id, "sections");
    $rootForums = qGetForumsBySectionId($section["id"], true, SORT::POSITION_ASCENDING);
?>

<table data-shclass="main-table" class="main-table">
    <caption data-shclass="caption captionbar" class="captionbar">
        <a href="section.php?id=<?=$section["id"]?>"><?=$section["title"]?></a>
    </caption>

    <?php foreach ($rootForums as $rootForum): ?>
        <?php $childForums = qGetForumsByParentId($rootForum["id"], SORT::POSITION_ASCENDING); ?>

        <tr data-shclass="table-row" class="table-row">
            <td>
                <span class="icon icon-forum-new"></span>
                <a data-shclass="row-name" href="forum.php?id=<?=$rootForum["id"]?>" class="name"><?=$rootForum["title"]?></a>
                <span class="desc"><?=$rootForum["description"]?></span>
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

<?php /*
    <tr data-shclass="table-row" class="table-row">
        <td>
            <span class="icon icon-forum-redirect"></span>
            <a data-shclass="row-name" href="" class="name">Preusmeravanje, reklame?</a>
        </td>
        <td colspan="2"><strong>0</strong> klika</td>
    </tr>
*/ ?>