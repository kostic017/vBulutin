<?php
    require_once "header.php";
    $sections = qGetSections(SORT::POSITION_ASCENDING);
?>

<main>

    <?php
        foreach ($sections as $section) {
            require "tablesection.php";
        }
    ?>

</main>

<?php require_once "footer.php"; ?>
