<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function isEqualToAnyWord(string $haystack, string $needle, bool $ignoreCase = true): bool {
        $words = explode(" ", $haystack);

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
