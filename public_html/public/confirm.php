<?php
    require_once __DIR__ . "/scripts/php/temp.php";

    if (isset($_GET["email"]) && isset($_GET["token"])) {
       qConfirmEmail($_GET); 
    } else {
        redirectTo("register.php");
    }