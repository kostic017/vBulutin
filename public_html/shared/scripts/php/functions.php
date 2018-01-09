<?php
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
