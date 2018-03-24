<?php
    require_once "header.php";
    require_once "scripts/php/includes.php";

    $sections = qGetRowsByTableName("sections", SORT::POSITION_ASCENDING);
?>

<main>
    <p id="message"></p>

    <div class="positioning-buttons">
        <div>
            <form action="" method="post">
                <button type="button" name="save">Sačuvaj</button>
            </form>
        </div>
        <div class="sections-tree-controls collapse-buttons">
            <button type="button">-</button>
            <button type="button">+</button>
        </div>
    </div>

    <div class="sortable-sections collapse-buttons">

        <?php foreach ($sections ?? [] as $section): ?>
            <?php $rootForums = qGetForumsBySectionId($section["id"], true, SORT::POSITION_ASCENDING); ?>

            <div class="sortable-section">

                <div class="section-header">
                    <div>
                        <button class="section-tree-control" data-action="collapse"></button>
                        (<?=$section["id"]?>) <?=$section["title"]?>
                    </div>
                    <div class="forums-tree-controls">
                        <button type="button">-</button>
                        <button type="button">+</button>
                    </div>
                </div>


                <div class="dd" data-sectionid="<?=$section["id"]?>">

                    <ol class="dd-list">

                        <?php foreach ($rootForums ?? [] as $rootForum): ?>
                            <?php $childForums =
                                qGetForumsByParentId($rootForum["id"], SORT::POSITION_ASCENDING); ?>

                            <li class="dd-item" data-id="<?=$rootForum["id"]?>">

                                <div class="dd-handle">
                                    (<?=$rootForum["id"]?>) <?=$rootForum["title"]?>
                                </div>

                                <?php if (count($childForums) > 0): ?>
                                    <ol class="dd-list">
                                        <?php foreach ($childForums as $childForum): ?>
                                            <li class="dd-item" data-id="<?=$childForum["id"]?>">
                                                <div class="dd-handle">
                                                    (<?=$childForum["id"]?>) <?=$childForum["title"]?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ol>
                                <?php endif; ?>

                            </li>

                        <?php endforeach; ?>

                    </ol>

                </div>

            </div>

        <?php endforeach; ?>

    </div>
</main>
