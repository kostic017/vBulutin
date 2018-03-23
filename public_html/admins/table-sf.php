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
                    $_POST["visible"], $_POST["parentid"], $_POST["sections_id"]);
            break;
            case "sections":
                qInsertSection($_POST["title"], $_POST["description"], $_POST["visible"]);
            break;
        }
    }

    if (isset($_POST["delete"])) {
        $redirect = true;
        $id = explode("_", $_POST["delete"])[1];
        if (hasString($_POST["delete"], "forums")) {
            qDeleteForum($id);
        } elseif (hasString($_POST["delete"], "sections")) {
            qDeleteSection($id);
        }
    }

    if ($redirect) {
        redirectTo("table-sf.php");
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
