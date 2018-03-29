<?php
    require_once "header.php";
    $section = qGetRowById($thisPageId, "sections");
?>

<main>

    <?php
        require_once "topbox.php";
        require_once "sectable.php";
    ?>

</main>

<?php require_once "footer.php"; ?>
