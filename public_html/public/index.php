<?php
    include "header.php";
    $sections = qGetSections(SORT::POSITION_ASCENDING);
?>

<main>

    <?php
        foreach ($sections as $section) {
            $id = $section["id"];
            include "tablesection.php";
        }
    ?>
    
</main>

<?php include "footer.php"; ?>
