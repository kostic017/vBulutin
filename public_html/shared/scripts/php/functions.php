<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function hashPassword($password) {
        return md5($password);
    }

    function orderByStatement($sort) {
        $sql = "ORDER BY ";
        $counter = count($sort);
        foreach ($sort as $column => $direction) {
            $sql .= "{$column} {$direction}";
            $sql .= (--$counter > 0) ? ", " : " ";
        }
        return $sql;
    }

    function sendEmail($email, $subject, $body, $html = false) {
        require_once __DIR__ . "/../../libraries/PHPMailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->isHTML($html);
        $mail->CharSet = "UTF-8";
        $mail->SMTPDebug = SMTP_DEBUG;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        try {
            $mail->setFrom(MAIL_USERNAME, "Forum41");
        } catch (Exception $e) {
            echo "PHPMailer Exception: {$e->getMessage()}";
        }

        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $body;

        try {
            $mail->send();
        } catch (Exception $e) {
            echo "PHPMailer Exception: {$e->getMessage()}";
        }
    }

    function getDatetime() {
        return date("Y-m-d H:i:s");
    }

    function getNewDom() {
        return new DOMDocument('1.0', 'utf-8');
    }

    function parseJsonFile($path) {
        $contents = file_get_contents($path);
        $contents = utf8_encode($contents);
        return json_decode($contents);
    }

    function q($string = "") {
        return "'{$string}'";
    }

    function h($string = "") {
        return htmlspecialchars($string);
    }

    function u($string = "") {
        return urlencode($string);
    }

    function redirectTo($location) {
        echo "<script>location.replace('{$location}');</script>"; // TODO
    }

    function isPostRequest() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    function isNotBlank($value) {
        return isset($value) && trim($value) !== "";
    }

    function hasString($haystack, $needle) {
        return strpos($haystack, $needle) !== false;
    }

    function isEqualToAnyWord($haystack, $needle) {
        $strings = explode(" ", $haystack);
        foreach ($strings as $string) {
            if ($string === $needle) {
                return true;
            }
        }
        return false;
    }

    function printNestedArray($a) {
        echo '<blockquote>';
        foreach ($a as $key => $value) {
            echo h("$key: ");
            if (is_array($value)) {
                printNestedArray($value);
            } else {
                echo h($value) . '<br />';
            }
        }
        echo '</blockquote>';
    }
