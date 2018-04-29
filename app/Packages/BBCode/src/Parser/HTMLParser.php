<?php
/**
 * Created by PhpStorm.
 * User: genertorg
 * Date: 13/07/2017
 * Time: 13:11
 */

namespace App\Packages\BBCode\Parser;

final class HTMLParser extends Parser
{
    protected $parsers = [
        'font' => [
            'pattern' => '/<span style="font-family: (.+?);">(.*?)<\/span>/s',
            'replace' => '[font=$1]$2[/font]',
            'content' => '$2'
        ],
        'size' => [
            'pattern' => '/<span style="font-size: (.+?);">(.*?)<\/span>/s',
            'replace' => '[size=$1]$2[/size]',
            'content' => '$2'
        ],
        'color' => [
            'pattern' => '/<span style="color: (.+?);">(.*?)<\/span>/s',
            'replace' => '[color=$1]$2[/color]',
            'content' => '$2'
        ],
        'rtl' => [
            'pattern' => '/<div style="direction: rtl;">(.*?)<\/div>/s',
            'replace' => '[rtl]$1[/rtl]',
            'content' => '$1'
        ],
        'left' => [
            'pattern' => '/<div class="text-left">(.*?)<\/div>/s',
            'replace' => '[left]$1[/left]',
            'content' => '$1'
        ],
        'center' => [
            'pattern' => '/<div class="text-center">(.*?)<\/div>/s',
            'replace' => '[center]$1[/center]',
            'content' => '$1'
        ],
        'right' => [
            'pattern' => '/<div class="text-right">(.*?)<\/div>/s',
            'replace' => '[right]$1[/right]',
            'content' => '$1'
        ],
        'justify' => [
            'pattern' => '/<div class="text-justify">(.*?)<\/div>/s',
            'replace' => '[justify]$1[/justify]',
            'content' => '$1'
        ],
        'horizontalrule' => [
            'pattern' => '/<hr>/',
            'replace' => '[hr]',
            'content' => '',
        ],
        'bold' => [
            'pattern' => '/<strong>(.*?)<\/strong>|<b>(.*?)<\/b>/s',
            'replace' => '[b]$1[/b]',
            'content' => '$1',
        ],
        'italic' => [
            'pattern' => '/<i>(.*?)<\/i>|<em>(.*?)<\/em>/s',
            'replace' => '[i]$1[/i]',
            'content' => '$1'
        ],
        'underline' => [
            'pattern' => '/<u>(.*?)<\/u>/s',
            'replace' => '[u]$1[/u]',
            'content' => '$1',
        ],
        'linethrough' => [
            'pattern' => '/<s>(.*?)<\/s>|<del>(.*?)<\/del>/s',
            'replace' => '[s]$1[/s]',
            'content' => '$1',
        ],
        'code' => [
            'pattern' => '/<code>(.*?)<\/code>/s',
            'replace' => '[code]$1[/code]',
            'content' => '$1'
        ],
        // 'orderedlistnumerical' => [
        //     'pattern' => '/<ol>(.*?)<\/ol>/s',
        //     'replace' => '[list=1]$1[/list]',
        //     'content' => '$1'
        // ],
        // 'unorderedlist' => [
        //     'pattern' => '/<ul>(.*?)<\/ul>/s',
        //     'replace' => '[list]$1[/list]',
        //     'content' => '$1'
        // ],
        // 'listitem' => [
        //     'pattern' => '/<li>(.*?)<\/li>/s',
        //     'replace' => '[*]$1',
        //     'content' => '$1'
        // ],
        'orderedlistnumerical' => [
            'pattern' => '/<ol>(.*?)<\/ol>/s',
            'replace' => '[ol]$1[/ol]',
            'content' => '$1'
        ],
        'orderedlistalpha' => [
            'pattern' => '/<ol type="a">(.*?)<\/ol>/s',
            'replace' => '[ol=a]$1[/ol]',
            'content' => '$1'
        ],
        'unorderedlist' => [
            'pattern' => '/<ul>(.*?)<\/ul>/s',
            'replace' => '[ul]$1[/ul]',
            'content' => '$1'
        ],
        'listitem' => [
            'pattern' => '/<li>(.*?)<\/li>/s',
            'replace' => '[li]$1[/li]',
            'content' => '$1'
        ],
        'link' => [
            'pattern' => '/<a href="(.*?)">(.*?)<\/a>/s',
            'replace' => '[url=$1]$2[/url]',
            'content' => '$1'
        ],
        'quote' => [
            'pattern' => '/<blockquote>(.*?)<\/blockquote>/s',
            'replace' => '[quote]$1[/quote]',
            'content' => '$1'
        ],
        'image' => [
            'pattern' => '/<img src="(.*?)">/s',
            'replace' => '[img]$1[/img]',
            'content' => '$1'
        ],
        'youtube' => [
            'pattern' => '/<iframe width="560" height="315" src="\/\/www\.youtube\.com\/embed\/(.*?)" frameborder="0" allowfullscreen><\/iframe>/s',
            'replace' => '[youtube]$1[/youtube]',
            'content' => '$1'
        ],
        'linebreak' => [
            'pattern' => '/<br\s*\/?>/',
            'replace' => '/\r\n/',
            'content' => '',
        ],
        'sub' => [
            'pattern' => '/<sub>(.*?)<\/sub>/s',
            'replace' => '[sub]$1[/sub]',
            'content' => '$1'
        ],
        'sup' => [
            'pattern' => '/<sup>(.*?)<\/sup>/s',
            'replace' => '[sup]$1[/sup]',
            'content' => '$1'
        ],
        'small' => [
            'pattern' => '/<small>(.*?)<\/small>/s',
            'replace' => '[small]$1[/small]',
            'content' => '$1',
        ],
        'table' => [
            'pattern' => '/<table>(.*?)<\/table>/s',
            'replace' => '[table]$1[/table]',
            'content' => '$1',
        ],
        'table-row' => [
            'pattern' => '/<tr>(.*?)<\/tr>/s',
            'replace' => '[tr]$1[/tr]',
            'content' => '$1',
        ],
        'table-data' => [
            'pattern' => '/<td>(.*?)<\/td>/s',
            'replace' => '[td]$1[/td]',
            'content' => '$1',
        ],
    ];

    public function parse(string $source): string
    {
        foreach ($this->parsers as $name => $parser) {
            $source = $this->searchAndReplace($parser['pattern'], $parser['replace'], $source);
        }

        return $source;
    }
}
