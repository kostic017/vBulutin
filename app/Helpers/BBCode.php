<?php

namespace App\Helpers;

class BBCode {
    private static $parser = null;

    public static function parse(string $bbcode): string
    {
        if (!self::$parser) {
            self::$parser = new \App\Packages\ChrisKonnertz\BBCode\BBCode();

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
                return $tag->opening ? '<div class="text-justify">' : '</div>';
            });

            self::$parser->addTag('rtl', function($tag, &$html, $openingTag) {
                return $tag->opening ? '<div style="direction: rtl;">' : '</div>';
            });
        }
        return self::$parser->render($bbcode);
    }
}
