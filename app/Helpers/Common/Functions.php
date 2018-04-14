<?php

namespace App\Helpers\Common;

abstract class Functions {

    public static function isEqualToAnyWord(string $haystack, string $needle, bool $ignoreCase = true): bool
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

}
