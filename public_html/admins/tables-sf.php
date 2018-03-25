<?php
    require_once "header.php";
    require_once "scripts/php/includes.php";

    $redirect = false;

    if (isset($_POST["clear"])) {
        $redirect = true;
        qClearTable($_POST["clear"]);
    }

    if (isset($_POST["insert"])) {
        $redirect = true;
        switch ($_POST["insert"]) {
            case "forums":
                gInsertForum($_POST["title"], $_POST["description"],
                    $_POST["visible"], $_POST["parentId"], $_POST["sectionId"]);
            break;
            case "sections":
                qInsertSection($_POST["title"], $_POST["description"], $_POST["visible"]);
            break;
        }
    }

    if (isset($_POST["delete"])) {
        $redirect = true;
        $id = explode("_", $_POST["delete"])[1];
        if (hasSubstring($_POST["delete"], "forums")) {
            qDeleteForum($id);
        } elseif (hasSubstring($_POST["delete"], "sections")) {
            qDeleteSection($id);
        }
    }

    if (isset($_POST["update"])) {
        $redirect = true;
        $id = explode("_", $_POST["update"])[1];
        if (hasSubstring($_POST["update"], "forums")) {
            qUpdateForum($id, $_POST["title"], $_POST["description"], $_POST["visible"], $_POST["sectionId"]);
        } elseif (hasSubstring($_POST["update"], "sections")) {
            qUpdateSection($id, $_POST["title"], $_POST["description"], $_POST["visible"]);
        }
    }

    if ($redirect) {
        redirectTo("tables-sf.php");
    }
?>

<script src="scripts/js/table.js"></script>

<main>

    <h2 class="table-title">Sekcije</h2>
    <p>Brisanjem sekcije brišete sve forume koje pripadaju toj sekciji kao i teme u tim forumima.</p>

    <form method="post" action="">
        <?php
            $tableName = "sections";
            require "table-data.php";
        ?>
    </form>

    <h2 class="table-title">Forumi</h2>

    <form method="post" action="">
        <?php
            $tableName = "forums";
            require "table-data.php";
        ?>
    </form>

</main>

<div id="overlay"></div>
