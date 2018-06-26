<?php

function alert_redirect($url, $level, $message)
{
    return redirect($url)->with([
        'level' => $level,
        'message' => $message
    ]);
}

function is_captcha_set()
{
    return is_not_empty(config('custom.captcha.site_key')) && is_not_empty(config('custom.captcha.secret_key'));
}

function validate_captcha($response, $ip)
{
    $url = "https://www.google.com/recaptcha/api/siteverify?";
    $url .= "secret=" . config('custom.captcha.secret_key') . "&";
    $url .= "response={$response}&";
    $url .= "remoteip={$ip}";
    $json = file_get_contents_curl($url);
    $result = json_decode($json);
    return $result->success;
}

function random_title($faker, $words, $unique = true)
{
    return rtrim($unique ? $faker->unique()->sentence($words) : $faker->sentence($words), '.');
}

function file_get_contents_curl($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function unique_slug($title, $id)
{
    return str_slug($title) . "." . $id;
}

function limit_words($value, $words = 3, $end = '...')
{
    return \Illuminate\Support\Str::words($value, $words, $end);
}

function extract_time($datetime)
{
    return \Carbon::parse($datetime)->format('H:i:s');
}

function extract_date($datetime)
{
    return \Carbon::parse($datetime)->format('d.m.Y');
}

function is_not_empty($str)
{
    if ($str === null) return false;
    return strlen(trim($str)) > 0;
}

function is_empty($str)
{
    return !is_not_empty($str);
}
