<?php

use Carbon\Carbon;

function validate_captcha($response, $ip)
{
    $url = "https://www.google.com/recaptcha/api/siteverify?";
    $url .= "secret=" . config('custom.captcha.secret_key') . "&";
    $url .= "response={$response}&";
    $url .= "remoteip={$ip}";
    $captchaResult = json_decode(file_get_contents($url));
    return $captchaResult->success;
}

function limit_words($value, $words = 3, $end = '...')
{
    return \Illuminate\Support\Str::words($value, $words, $end);
}

function extractTime($datetime)
{
    return Carbon::parse($datetime)->format('H:i:s');
}

function extractDate($datetime)
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

