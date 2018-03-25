<?php
    require_once "header.php";
    require_once "scripts/php/includes.php";

    $tableName = $_GET["name"] ?? "sections";

    switch ($tableName) {
        case "forums":
            $displayName = "Forumi";
        break;
        case "sections":
            $displayName = "Sekcije";
        break;
        default:
            $tableName = "sections";
            $displayName = "Sekcije";
        break;
    }

    if (isset($_POST["clear"]) || isset($_POST["insert"]) || isset($_POST["delete"]) || isset($_POST["update"])) {

        if (isset($_POST["clear"])) {
            qClearTable($tableName);
        }

        elseif (isset($_POST["insert"])) {
            switch ($tableName) {
                case "forums":
                    gInsertForum($_POST["title"], $_POST["description"], $_POST["visible"], $_POST["parentId"], $_POST["sectionId"]);
                break;
                case "sections":
                    qInsertSection($_POST["title"], $_POST["description"], $_POST["visible"]);
                break;
            }
        }

        elseif (isset($_POST["delete"])) {
            switch ($tableName) {
                case "forums":
                    qDeleteForum($_POST["delete"]);
                break;
                case "sections":
                    qDeleteSection($_POST["delete"]);
                break;
            }
        }

        elseif (isset($_POST["update"])) {
            switch ($tableName) {
                case "forums":
                    qUpdateForum($_POST["update"], $_POST["title"], $_POST["description"], $_POST["visible"], $_POST["sectionId"]);
                break;
                case "sections":
                    qUpdateSection($_POST["update"], $_POST["title"], $_POST["description"], $_POST["visible"]);
                break;
            }
        }

        redirectTo("table.php?name={$tableName}");
    }
?>

<script src="scripts/js/table.js"></script>

<main>

    <h2 class="table-title" data-name="<?=$tableName?>"><?=$displayName?></h2>

    <form method="post" action="">
        <?php require "table-data.php"; ?>
    </form>

</main>

<div id="overlay"></div>
