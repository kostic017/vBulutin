<?php
    session_start();
    require_once __DIR__ . "/queries.php";

    /* ==========================================================================
       SIDEBAR
       ========================================================================== */
    $sidebarItems = ["welcome", "newtopics", "newmessages", "latestfiles", "populartags",
        "recentstatuses", "upcomingevents", "tellafriend"];
    $isSidebarSet = isset($sidebarItems) && !empty($sidebarItems);

    /* ==========================================================================
       NAVIGATION
       ========================================================================== */
    $forumNavigation = [
        "Početna"          => "index",
        "Pravilnik"        => "rules",
        "Članovi"          => "members",
        "Korisnicke grupe" => "groups",
        "Ćaskanje"         => "chat"
    ];