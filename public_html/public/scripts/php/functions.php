<?php

    function getPageId() {
        if (isNotBlank($id = $_GET["id"] ?? "")) {
            return $id;
        } else {
            redirectTo("/public/");
        }
    }

    function sendForgottedData($what, $email) {
        if ($what === "username") {
            if (isNotBlank($username = qGetUsernameByEmail($email))) {
                sendEmail($email, "Forum41: Zaboravljeno korisničko ime",
                    "Vaše korisničko ime je:<br><code>{$username}</code>", true);
            }
        } else {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
            $password = substr(str_shuffle(sha1(rand() . time()) . $chars), 0, rand(10, 16));

            qSetNewPassword(qGetUserIdByEmail($email), $password);

            sendEmail($email, "Forum41: Zaboravljena lozinka",
                "Vaša nova lozinka je:<br><code>{$password}</code>", true);
        }
    }

    function sendEmailConfirmation($email) {
        $body = "Kliknite na link da bi potvrdili svoju email adresu: ";
        $body .= "{$_SERVER["SERVER_NAME"]}/public/confirm.php?email={$email}";
        sendEmail($email, "Forum41: Potvrđivanje email adrese", $body);
    }
