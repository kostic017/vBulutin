<?php
    $thisPage = qGetRowById($thisPageId, FILENAME . "s"); // section.s, forum.s...

    if (FILENAME === "section") {
        $follow = "ovu sekciju";

        $pathSectionTitle = $thisPage["title"];
        $pathSectionLink = "#";
    } elseif (FILENAME === "forum") {
        $follow = "ovaj forum";

        $section = qGetRowById($thisPage["sectionId"], "sections");

        if (isNotBlank($thisPage["parentId"])) {
            $parent = qGetRowById($thisPage["parentId"], "forums");
            $pathParentTitle = $parent["title"];
            $pathParentLink = "forum.php?id={$parent["id"]}";
        }

        $pathSectionTitle = $section["title"];
        $pathSectionLink = "section.php?id={$thisPage["sectionId"]}";

        $pathForumTitle = $thisPage["title"];
        $pathForumLink = "#";
    } elseif (FILENAME === "topic") {
        $follow = "ovu temu";

        $forum = qGetRowById($thisPage["forumId"], "forums");

        $pathForumTitle = $forum["title"];
        $pathForumLink = "forum.php?id={$forum["id"]}";

        $section = qGetRowById($forum["sectionId"], "sections");

        $pathSectionTitle = $section["title"];
        $pathSectionLink = "section.php?id={$section["id"]}";
    }
?>

<div class="top-box">

    <nav class="path">
        <ul>
            <li><a href="index.php">FORUM_NAME</a></li>
            <li><a href="<?=$pathSectionLink?>"><?=$pathSectionTitle?></a></li>
            <?php if (isset($pathParentTitle)): ?>
                <li><a href="<?=$pathParentLink?>"><?=$pathParentTitle?></a></li>
            <?php endif; ?>
            <?php if (isset($pathForumTitle)): ?>
                <li><a href="<?=$pathForumLink?>"><?=$pathForumTitle?></a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="page-info">

        <div data-shclass="page-title" class="title">
            <?php if (isEqualToAnyWord("forum section", FILENAME)): ?>
                <h1><?=$thisPage["title"]?></h1>

                <?php if (isNotBlank($thisPage["description"])): ?>
                    <p class="desc"><?=$thisPage["description"]?></p>
                <?php endif; ?>

                <?php if (FILENAME === "forum"): ?>
                    <div class="mods">
                        Moderatori:
                        <ul>
                            <li><a href="">Pera</a></li>
                            <li><a href="">Zika</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php elseif (FILENAME === "topic"): ?>
                <h1><?=$thisPage["title"]?></h1>
            <?php endif; ?>
        </div>

        <div id="btn-follow">
            <button>Prati <?=$follow?></button>
            <span>28</span>
        </div>

    </div>

    <?php if (FILENAME !== "section"): ?>

        <div class="page-buttons">

            <div class="pages">
                Stranica 1 od 107
                <ul>
                    <li data-shclass="active-page"><a href="">1</a></li>
                    <li><a href="">2</a></li>
                    <li><a href="">3</a></li>
                    <li><a href="">Next</a></li>
                    <li><a href="">&rsaquo;&rsaquo;</a></li>
                </ul>
            </div>

            <div>
                <?php if (FILENAME === "forum"): ?>
                    <a href="" id="btn-mark-read">Označi ovaj forum kao pročitan</a>
                <?php endif; ?>
            </div>

        </div>

    <?php endif; ?>

</div>
