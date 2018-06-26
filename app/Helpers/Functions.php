<?php

function route_category_show($category)
{
    return route('public.category.show', [
        'board_url' => $category->board()->firstOrFail()->url,
        'category_slug' => $category->slug,
    ]);
}

function route_forum_show($forum)
{
    return route('public.forum.show', [
        'board_url' => $forum->board()->firstOrFail()->url,
        'category_slug' => $forum->category()->firstOrFail()->slug,
        'forum_slug' => $forum->slug,
    ]);
}

function route_topic_show($topic)
{
    return route('public.topic.show', [
        'board_url' => $topic->board()->firstOrFail()->url,
        'category_slug' => $topic->category()->firstOrFail()->slug,
        'forum_slug' => $topic->forum()->firstOrFail()->slug,
        'topic_slug' => $topic->slug
    ]);
}

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
