<?php
    $data = qGetRowById($id, FILENAME . "s");

    if (FILENAME === "section") {
        $follow = "ovu sekciju";
        $pathSection = $data["title"];
    } else if (FILENAME === "forum") {
        $follow = "ovaj forum";
        $reply = "Započni novu temu";
        $section = qGetRowById($data["sections_id"], "sections");
        $pathSection = $section["title"];
        $pathForum = $data["title"];
    } else if (FILENAME === "topic") {
        $follow = "ovu temu";
        $reply = "Napiši odgovor";
    }
?>

<div class="top-box">

    <nav class="path">
        <ul>
            <li><a href="">FORUM_NAME</a></li>
            <li><a href=""><?=$pathSection?></a></li>
            <?php if (isset($pathForum)): ?>
                <li><a href=""><?=$pathForum?></a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="page-info">

        <div data-shclass="page-title" class="title">
            <?php if (isEqualToAnyWord("forum section", FILENAME)): ?>
                <h1><?=$data["title"]?></h1>
                
                <?php if (isNotBlank($data["description"])): ?>
                    <p class="desc"><?=$data["description"]?></p>
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
                <button data-shclass="btn-reply" id="btn-reply"><?=$reply?></button>
            </div>

        </div>
        
    <?php endif; ?>

</div>