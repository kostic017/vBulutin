<?php

use App\User;
use App\UserWatches;
use App\UserModerates;
use Carbon\Carbon;

function alert_redirect($url, $level, $message)
{
    return redirect($url)->with([
        'level' => $level,
        'message' => $message
    ]);
}

function is_admin()
{
    return Auth::user()->is_admin ?? false;
}

function getWatchers($myTable, $myId)
{
    return User::findMany(UserWatches::select('user_id')->where("{$myTable}_id", $myId)->get()->toArray());
}

function getModerators($myTable, $myId)
{
    return User::findMany(UserModerates::select('user_id')->where("{$myTable}_id", $myId)->get()->toArray());
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

function extractTime($datetime)
{
    return Carbon::parse($datetime)->format('H:i:s');
}

function extractDate($datetime)
{
    return Carbon::parse($datetime)->format('d.m.Y');
}

function isNotEmpty($str)
{
    if ($str === null) return false;
    return strlen(trim($str)) > 0;
}

function isEmpty($str)
{
    return !isNotEmpty($str);
}

function isEqualToAnyWord($haystack, $needle, $ignoreCase = true)
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

