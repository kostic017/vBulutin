<?php

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

