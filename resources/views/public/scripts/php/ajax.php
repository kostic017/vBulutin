<?php
    require_once "../../../shared/scripts/php/main.php";


    if (isset($_POST["job"])) {

        switch ($_POST["job"]) {

            case "post_info":
                if ($post = qGetRowById($_POST["id"], "posts")) {
                    if ($user = qGetRowById($post["userId"], "users")) {
                        echo json_encode([
                            "id" => $post["id"],
                            "content" => $post["content"],
                            "postedDT" => $post["postedDT"],
                            "username" => $user["username"]
                        ]);
                    }
                }
            break;

        }

    }