<?php
    require_once "header.php";
    $sections = qGetSections(SORT::POSITION_ASCENDING);
?>

<main>

    <?php
        foreach ($sections as $section) {
            require "sectiontable.php";
        }
    ?>

</main>

<?php require_once "footer.php"; ?>
