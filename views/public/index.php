<?php
    require_once "header.php";
    $sections = qGetRowsByTableName("sections", SORT::POSITION_ASCENDING);
?>

<main>

    <?php
        if ($sections) {
            foreach ($sections as $section) {
                require "sectable.php";
            }
        }
    ?>

</main>

<?php require_once "footer.php"; ?>
