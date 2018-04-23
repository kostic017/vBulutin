<?php

function limit_words($value, $words = 3, $end = '...')
{
    return \Illuminate\Support\Str::words($value, $words, $end);
}

function extractTime($datetime)
{
    return $datetime->format('H:i:s');
}

function extractDate($datetime)
{
    return $datetime->format('d.m.Y');
}

function isNotEmpty(string $str)
{
    return strlen(trim($str)) > 0;
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

