<?php
    require_once __DIR__ . "/scripts/php/includes.php";
    require_once $TEMPLATE_HEADER_PATH;

    if (isPostRequest()) {
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
    }
?>
</head>

<body>
<?php require_once __DIR__ . "/navigation.php"; ?>

<main>
    <h2 class="table-title">Sekcije</h2>
    <p>Brisanjem sekcije brišete sve forume koje pripadaju toj sekciji kao i teme u tim forumima.</p>

    <form method="post" action="">
        <?=getDataTable("sections")?>
    </form>

    <h2 class="table-title">Forumi</h2>

    <form method="post" action="">
        <?=getDataTable("forums")?>
    </form>
</main>

<div id="overlay"></div>
