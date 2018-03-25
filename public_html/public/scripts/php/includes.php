<?php
    session_start();
    require_once __DIR__ . "/../../../shared/scripts/php/main.php";
    require_once __DIR__ . "/queries.php";
    require_once __DIR__ . "/functions.php";

    /* ==========================================================================
       SIDEBAR
       ========================================================================== */
    $sidebarItems = ["welcome", "newtopics", "newposts", "latestfiles", "populartags",
        "recentstatuses", "upcomingevents", "tellafriend"];
    $isSidebarSet = isset($sidebarItems) && !empty($sidebarItems);

    /* ==========================================================================
       NAVIGATION
       ========================================================================== */
    $forumNavigation = [
        "Početna" => "index",
        "Pravilnik" => "rules",
        "Članovi" => "members",
        "Korisnicke grupe" => "groups",
        "Ćaskanje" => "chat"
    ];
