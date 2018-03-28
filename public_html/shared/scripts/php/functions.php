<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function echoShorten($string) {
        echo trim(substr($string, 0, SHORTEN_LIMIT)) . "...";
    }

    function dateDifference($date1, $date2, $differenceFormat = "%a") {
        $datetime1 = date_create($date1);
        $datetime2 = date_create($date2 ?? getDatetimeForMysql());

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }

    function getPHPDate() {
        return strftime("%d. %B %Y.");
    }

    function getPHPDateTime() {
        return strftime("%d. %B %Y. %H:%M");
    }

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

    function redirectTo($url, $timeout = 0) {
        echo "<script>redirectTo('{$url}', {$timeout});</script>";
    }

    function isNotBlank($value) {
        return isset($value) && trim($value) !== "";
    }

    function hasSubstring($haystack, $needles) {
        if (is_array($needles)) {
            foreach ($needles as $needle) {
                if (strpos($haystack, $needle)) {
                    return true;
                }
            }
        } else {
            return strpos($haystack, $needles) !== false;
        }
        return false;
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
