<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    function sendEmailConfirmation($email) {
        require_once __DIR__ . "/../../libraries/PHPMailer/PHPMailerAutoload.php";
        
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        // $mail->SMTPDebug = 3;
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
        $mail->setFrom(MAIL_USERNAME, "Forum41");

        $mail->addAddress($email);
        $mail->Subject = "Forum41: Potvrđivanje email adrese";

        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $link = "{$_SERVER["SERVER_NAME"]}/public/confirm.php?email={$email}&token={$token}";
       
        $mail->Body = "Kliknite na link da bi potvrdili svoju email adresu: {$link}.";

        if (!$mail->send()) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
       
        return $token;
    }

    function convertMysqlDatetimeToPhpDate($datetime) {
        $datetime = strtotime($datetime);
        return date("j. F Y.", $datetime);
    }
    
    function convertMysqlDatetimeToPhpTime($datetime) {
        $datetime = strtotime($datetime);
        return date("G:i:s", $datetime);
    }

    function echoCode($code) {
        echo "<pre>{$code}</pre>";
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
        header("Location: " . $location);
        exit;
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
