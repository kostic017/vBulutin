<?php
    require_once "header.php";

    if (($id = $_GET["id"] ?? "") === "") {
        redirectTo("/public/");
    }
?>

<main>

    <?php
        include "topbox.php";
        include "section-table.php";
    ?>

</main>

<?php include "footer.php"; ?>
