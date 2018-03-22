<?php
    require_once "header.php";
    require_once "scripts/php/includes.php";

    if (isset($_POST["forums_insert"])) {
        gInsertForum($_POST);
    } elseif (isset($_POST["forums_update"])) {
        qUpdateForum($_POST);
    } elseif (isset($_POST["forums_delete"])) {
        qDeleteForum($_POST["forums_delete"]);
    } elseif (isset($_POST["sections_insert"])) {
        qInsertSection($_POST);
    } elseif (isset($_POST["sections_update"])) {
        qUpdateSection($_POST);
    } elseif (isset($_POST["sections_delete"])) {
        qDeleteSection($_POST["sections_delete"]);
    } elseif (isset($_POST["forums_clear"])) {
        qClearTable("forums");
    } elseif (isset($_POST["sections_clear"])) {
        qClearTable("sections");
    }
?>

<main>
    <h2 class="table-title">Sekcije</h2>
    <p>Brisanjem sekcije brišete sve forume koje pripadaju toj sekciji kao i teme u tim forumima.</p>

    <form method="post" action="">
        <?php
            $tableName = "sections";
            require "datatable.php";
        ?>
    </form>

    <h2 class="table-title">Forumi</h2>

    <form method="post" action="">
        <?php
            $tableName = "forums";
            require "datatable.php";
        ?>
    </form>
</main>

<div id="overlay"></div>
