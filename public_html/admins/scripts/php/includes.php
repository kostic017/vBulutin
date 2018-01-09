<?php
    $PUBLIC_HTML_PATH = $_SERVER['DOCUMENT_ROOT'];
    require_once "{$PUBLIC_HTML_PATH}/shared/scripts/php/main.php";

    require_once __DIR__ . "/functions.php";
    require_once __DIR__ . "/queries.php";

    $TEMPLATE_HEADER_PATH = "{$PUBLIC_HTML_PATH}/shared/templates/header.php";