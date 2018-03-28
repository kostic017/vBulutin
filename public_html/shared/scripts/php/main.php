<?php
    error_reporting(E_ALL | E_STRICT);

    ini_set('display_errors', 1);
    ini_set("xdebug.var_display_max_depth", 10);

    setlocale(LC_ALL, "Serbian");
    date_default_timezone_set("Europe/Belgrade");

    require_once __DIR__ . "/constants.php";
    require_once __DIR__ . "/functions.php";
    require_once __DIR__ . "/dbfunctions.php";
    require_once __DIR__ . "/queries.php";

    if ($db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)) {
        mysqli_set_charset($db, "utf8mb4");
    } else {
        exit(sprintf("Database connection failed: %s (%d).",
            h(mysqli_connect_error()), h(mysqli_connect_errno())
        ));
    }
