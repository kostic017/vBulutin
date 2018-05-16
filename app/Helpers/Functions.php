<?php

use Carbon\Carbon;

function validate_captcha(string $response, string $ip): bool
{
    $url = "https://www.google.com/recaptcha/api/siteverify?";
    $url .= "secret=" . config('custom.captcha.secret_key') . "&";
    $url .= "response={$response}&";
    $url .= "remoteip={$ip}";
    $json = file_get_contents_curl($url);
    $result = json_decode($json);
    return $result->success;
}

function file_get_contents_curl(string $url): string {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function limit_words(string $value, int $words = 3, string $end = '...'): string
{
    return \Illuminate\Support\Str::words($value, $words, $end);
}

function extractTime(string $datetime): string
{
    return Carbon::parse($datetime)->format('H:i:s');
}

function extractDate(string $datetime): string
{
    return Carbon::parse($datetime)->format('d.m.Y');
}

function isNotEmpty(string $str): bool
{
    return strlen(trim($str)) > 0;
}

function isEmpty(string $str): bool {
    return !isNotEmpty($str);
}

function isEqualToAnyWord(string $haystack, string $needle, bool $ignoreCase = true): bool
{
    $words = explode(' ', $haystack);

    if ($ignoreCase) {
        $needle = strtolower($needle);
    }

    foreach ($words as $word) {
        if ($ignoreCase) {
            $word = strtolower($word);
        }

        if ($word === $needle) {
            return true;
        }
    }

    return false;
}

