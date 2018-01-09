<?php
    require_once __DIR__ . "/scripts/php/includes.php";

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

    require_once $TEMPLATE_HEADER_PATH;
?>
</head>

<body>

    <div class="jui-tabs">
      <ul>
        <li><a href="tables1.php">Sekcije i forumi</a></li>
        <li><a href="positioning.php">Pozicioniranje sekcija i foruma</a></li>
      </ul>
    </div>
