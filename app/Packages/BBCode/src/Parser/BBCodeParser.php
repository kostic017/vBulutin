<?php
/**
 * Created by PhpStorm.
 * User: genertorg
 * Date: 13/07/2017
 * Time: 13:20
 */

namespace App\Packages\BBCode\Parser;

final class BBCodeParser extends Parser
{
    protected $parsers = [
        'font' => [
            'pattern' => '/\[font=(.+?)\](.*?)\[\/font\]/s',
            'replace' => '<span style="font-family: $1;">$2</span>',
            'content' => '$2'
        ],
        'size' => [
            'pattern' => '/\[size=(.+?)\](.*?)\[\/size\]/s',
            'replace' => '<span style="font-size: $1;">$2</span>',
            'content' => '$2'
        ],
        'color' => [
            'pattern' => '/\[color=(.+?)\](.*?)\[\/color\]/s',
            'replace' => '<span style="color: $1;">$2</span>',
            'content' => '$2'
        ],
        'rtl' => [
            'pattern' => '/\[rtl\](.*?)\[\/rtl\]/s',
            'replace' => '<div style="direction: rtl;">$1</div>',
            'content' => '$1'
        ],
        'left' => [
            'pattern' => '/\[left\](.*?)\[\/left\]/s',
            'replace' => '<div class="text-left">$1</div>',
            'content' => '$1'
        ],
        'center' => [
            'pattern' => '/\[center\](.*?)\[\/center\]/s',
            'replace' => '<div class="text-center">$1</div>',
            'content' => '$1'
        ],
        'right' => [
            'pattern' => '/\[right\](.*?)\[\/right\]/s',
            'replace' => '<div class="text-right">$1</div>',
            'content' => '$1'
        ],
        'justify' => [
            'pattern' => '/\[justify\](.*?)\[\/justify\]/s',
            'replace' => '<div class="text-justify">$1</div>',
            'content' => '$1'
        ],
        'horizontalrule' => [
            'pattern' => '/\[hr\]/',
            'replace' => '<hr>',
            'content' => '',
        ],
        'bold' => [
            'pattern' => '/\[b\](.*?)\[\/b\]/s',
            'replace' => '<b>$1</b>',
            'content' => '$1'
        ],
        'italic' => [
            'pattern' => '/\[i\](.*?)\[\/i\]/s',
            'replace' => '<i>$1</i>',
            'content' => '$1'
        ],
        'underline' => [
            'pattern' => '/\[u\](.*?)\[\/u\]/s',
            'replace' => '<u>$1</u>',
            'content' => '$1'
        ],
        'linethrough' => [
            'pattern' => '/\[s\](.*?)\[\/s\]/s',
            'replace' => '<s>$1</s>',
            'content' => '$1'
        ],
        'quote' => [
            'pattern' => '/\[quote\](.*?)\[\/quote\]/s',
            'replace' => '<blockquote>$1</blockquote>',
            'content' => '$1'
        ],
        'link' => [
            'pattern' => '/\[url\](.*?)\[\/url\]/s',
            'replace' => '<a href="$1">$1</a>',
            'content' => '$1'
        ],
        'namedlink' => [
            'pattern' => '/\[url\=(.*?)\](.*?)\[\/url\]/s',
            'replace' => '<a href="$1">$2</a>',
            'content' => '$2'
        ],
        'image' => [
            'pattern' => '/\[img\](.*?)\[\/img\]/s',
            'replace' => '<img src="$1">',
            'content' => '$1'
        ],
        // 'orderedlistnumerical' => [
        //     'pattern' => '/\[list=1\](.*?)\[\/list\]/s',
        //     'replace' => '<ol>$1</ol>',
        //     'content' => '$1'
        // ],
        // 'orderedlistalpha' => [
        //     'pattern' => '/\[list=a\](.*?)\[\/list\]/s',
        //     'replace' => '<ol type="a">$1</ol>',
        //     'content' => '$1'
        // ],
        // 'unorderedlist' => [
        //     'pattern' => '/\[list\](.*?)\[\/list\]/s',
        //     'replace' => '<ul>$1</ul>',
        //     'content' => '$1'
        // ],
        // 'listitem' => [
        //     'pattern' => '/\[\*\](.*)/',
        //     'replace' => '<li>$1</li>',
        //     'content' => '$1'
        // ],
        'orderedlistnumerical' => [
            'pattern' => '/\[ol\](.*?)\[\/ol\]/s',
            'replace' => '<ol>$1</ol>',
            'content' => '$1'
        ],
        'orderedlistalpha' => [
            'pattern' => '/\[ol=a\](.*?)\[\/ol\]/s',
            'replace' => '<ol type="a">$1</ol>',
            'content' => '$1'
        ],
        'unorderedlist' => [
            'pattern' => '/\[ul\](.*?)\[\/ul\]/s',
            'replace' => '<ul>$1</ul>',
            'content' => '$1'
        ],
        'listitem' => [
            'pattern' => '/\[li\](.*)\[\/li\]/',
            'replace' => '<li>$1</li>',
            'content' => '$1'
        ],
        'code' => [
            'pattern' => '/\[code\](.*?)\[\/code\]/s',
            'replace' => '<code>$1</code>',
            'content' => '$1'
        ],
        'youtube' => [
            'pattern' => '/\[youtube\](.*?)\[\/youtube\]/s',
            'replace' => '<iframe width="560" height="315" src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
            'content' => '$1'
        ],
        'sub' => [
            'pattern' => '/\[sub\](.*?)\[\/sub\]/s',
            'replace' => '<sub>$1</sub>',
            'content' => '$1'
        ],
        'sup' => [
            'pattern' => '/\[sup\](.*?)\[\/sup\]/s',
            'replace' => '<sup>$1</sup>',
            'content' => '$1'
        ],
        'small' => [
            'pattern' => '/\[small\](.*?)\[\/small\]/s',
            'replace' => '<small>$1</small>',
            'content' => '$1'
        ],
        'table' => [
            'pattern' => '/\[table\](.*?)\[\/table\]/s',
            'replace' => '<table>$1</table>',
            'content' => '$1',
        ],
        'table-row' => [
            'pattern' => '/\[tr\](.*?)\[\/tr\]/s',
            'replace' => '<tr>$1</tr>',
            'content' => '$1',
        ],
        'table-data' => [
            'pattern' => '/\[td\](.*?)\[\/td\]/s',
            'replace' => '<td>$1</td>',
            'content' => '$1',
        ],
    ];

    public function stripTags(string $source): string
    {
        foreach ($this->parsers as $name => $parser) {
            $source = $this->searchAndReplace($parser['pattern'] . 'i', $parser['content'], $source);
        }

        return $source;
    }

    public function parse(string $source, $caseInsensitive = null): string
    {
        $caseInsensitive = $caseInsensitive === self::CASE_INSENSITIVE ? true : false;

        foreach ($this->parsers as $name => $parser) {
            $pattern = ($caseInsensitive) ? $parser['pattern'] . 'i' : $parser['pattern'];
            $source = $this->searchAndReplace($pattern, $parser['replace'], $source);
        }

        return $source;
    }
}
