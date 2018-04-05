@extends("admin.base")

@section("title")
    Pozicioniranje
@stop

@section("more-content")
    <p id="message">Snimanje uspešno izvršeno.</p>

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
@stop

@section("more-scripts")
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-nestable@0.8.0/jquery.nestable.min.js"></script>
@stop
