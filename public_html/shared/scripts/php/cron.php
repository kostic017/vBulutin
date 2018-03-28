<?php

    require_once __DIR__ . "/main.php";

    $sql = "SELECT id ";
    $sql .= "FROM users ";
    $sql .= "WHERE TIMEDIFF(NOW(), lastActivityDT) > " . LOGOUT_TIMEOUT_MINS;

    if ($user = qExecuteAndFetchAssoc($sql)) {
        $sql = "UPDATE users ";
        $sql .= "SET loggedIn='0' ";
        $sql .= "WHERE id='{$user["id"]}' ";
        executeQuery($sql);
        if (isset($_SESSION["userId"])) {
            unset($_SESSION["userId"][$user["id"]]);
        }
    }

    $sql = "DELETE FROM readTopics ";
    $sql .= "WHERE DATEDIFF(NOW(), timeDT) > " . GARBAGE_COLLECTION_DAYS;
    executeQuery($sql);