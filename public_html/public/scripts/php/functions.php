<?php

    function printErrors($errors) {
        if (!empty($errors)) {
            echo "<div>";
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    }

    function redirectBack($message = null) {
        if (isset($_SESSION["redirect_back"])) {
            $redirectBack = $_SESSION["redirect_back"];
            $disallow = ["redirect", "profile", "register"];
            $url = hasSubstring($redirectBack, $disallow) ? "index.php" : $redirectBack;

            if ($message) {
                $_SESSION["redirect"] = [
                    "url" => $url,
                    "message" => $message
                ];
                redirectTo("redirect.php");
            } else {
                redirectTo($url);
            }
        } else {
            $url = "index.php";
        }
    }

    function sendForgottedData($what, $email) {
        if ($what === "username") {
            if (isNotBlank($username = qGetUsernameByEmail($email))) {
                sendEmail($email, FORUM_NAME . ": Zaboravljeno korisničko ime",
                    "Vaše korisničko ime je:<br><code>{$username}</code>", true);
            }
        } else {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
            $password = substr(str_shuffle(sha1(rand() . time()) . $chars), 0, rand(10, 16));

            qSetNewPassword(qGetUserIdByEmail($email), $password);

            sendEmail($email, FORUM_NAME . ": Zaboravljena lozinka",
                "Vaša nova lozinka je:<br><code>{$password}</code>", true);
        }
    }

    function sendEmailConfirmation($email, $token) {
        $body = "Kliknite na link da bi potvrdili svoju email adresu: ";
        $body .= DOMAIN . "/public/confirm.php?email={$email}&token={$token}";
        sendEmail($email, FORUM_NAME . ": Potvrđivanje email adrese", $body);
    }
