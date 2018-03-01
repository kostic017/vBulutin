<?php
    $thisPage = qGetRowById($id, FILENAME . "s"); // section.s, forum.s...

    if (FILENAME === "section") {
        $follow = "ovu sekciju";

        $pathSectionTitle = $thisPage["title"];
        $pathSectionLink = "#";
    } elseif (FILENAME === "forum") {
        $follow = "ovaj forum";
        $reply = "Započni novu temu";

        $section = qGetRowById($thisPage["sections_id"], "sections");

        if (isNotBlank($thisPage["parentid"])) {
            $parent = qGetRowById($thisPage["parentid"], "forums");
            $pathParentTitle = $parent["title"];
            $pathParentLink = "forum.php?id={$parent["id"]}";
        }

        $pathSectionTitle = $section["title"];
        $pathSectionLink = "section.php?id={$thisPage["sections_id"]}";

        $pathForumTitle = $thisPage["title"];
        $pathForumLink = "#";
    } elseif (FILENAME === "topic") {
        $follow = "ovu temu";
        $reply = "Napiši odgovor";
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

        <div thisPage-shclass="page-title" class="title">
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
                <h1>Ime teme</h1>
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
                    <li thisPage-shclass="active-page"><a href="">1</a></li>
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
                <button thisPage-shclass="btn-reply" id="btn-reply"><?=$reply?></button>
            </div>

        </div>

    <?php endif; ?>

</div>
