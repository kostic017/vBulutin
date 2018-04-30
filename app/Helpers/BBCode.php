<?php

namespace App\Helpers;

class BBCode {
    private static $parser = null;

    public static function parse(string $bbcode): string
    {
        if (!self::$parser) {
            self::$parser = new \App\Packages\ChrisKonnertz\BBCode\BBCode();

            self::$parser->addTag('sub', function($tag, &$html, $openingTag) {
                return $tag->opening ? '<sub>' : '</sub>';
            });

            self::$parser->addTag('sup', function($tag, &$html, $openingTag) {
                return $tag->opening ? '<sup>' : '</sup>';
            });

            self::$parser->addTag('table', function($tag, &$html, $openingTag) {
                return $tag->opening ? '<table>' : '</table>';
            });

            self::$parser->addTag('tr', function($tag, &$html, $openingTag) {
                return $tag->opening ? '<tr>' : '</tr>';
            });

            self::$parser->addTag('td', function($tag, &$html, $openingTag) {
                return $tag->opening ? '<td>' : '</td>';
            });

            self::$parser->addTag('hr', function($tag, &$html, $openingTag) {
                return $tag->opening ? '<hr>' : '';
            });

            self::$parser->addTag('justify', function($tag, &$html, $openingTag) {
                return $tag->opening ? '<p class="text-justify">' : '</p>';
            });

            self::$parser->addTag('rtl', function($tag, &$html, $openingTag) {
                return $tag->opening ? '<p style="direction: rtl;">' : '</p>';
            });
        }
        return self::$parser->render($bbcode);
    }

    private static function smiles(string $code): string
    {
        return $code;
    }
}
