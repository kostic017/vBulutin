<?php

namespace App\Helpers;

class BBCode {
    private static $parser = null;
    private static $emoticons = null;

    private const EMOTICONS_FOLDER = 'lib/sceditor/emoticons/';

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

        $code = self::$parser->render(e($bbcode), false);
        $code = self::parseSmiles($code);

        return $code;
    }

    private static function parseSmiles(string $code): string
    {
        if (!self::$emoticons) {
            self::$emoticons = [];
            $files = \File::allFiles(self::EMOTICONS_FOLDER);
            foreach ($files as $file) {
                self::$emoticons[] = [
                    'path' => '/' . self::EMOTICONS_FOLDER . $file->getFilename(),
                    'name' => $file->getBasename('.' . $file->getExtension()),
                ];
            }
        }

        foreach (self::$emoticons as $emoticon) {
            $pattern = "/:{$emoticon['name']}:/";
            $replace = "<img src=\"{$emoticon['path']}\" alt=\"{$emoticon['name']}\">";
            while (preg_match($pattern, $code)) {
                $code = preg_replace($pattern, $replace, $code);
            }
        }

        return $code;
    }
}
