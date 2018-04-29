<?php

namespace App\Helpers;

use App\Packages\BBCode\BBCode as Parser;

class BBCode {
    private static $parser = null;

    public static function parse(string $bbcode): string
    {
        if (!self::$parser) {
            self::$parser = new Parser;
        }
        return self::$parser->convertToHtml($bbcode);
    }
}
