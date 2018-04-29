<?php
/**
 * Created by PhpStorm.
 * User: genertorg
 * Date: 13/07/2017
 * Time: 12:16
 */

namespace App\Packages\BBCode\Parser;

class Parser
{
    /**
     * Static case insensitive flag to enable
     * case insensitivity when parsing BBCode.
     */
    const CASE_INSENSITIVE = 0;

    protected $parsers = [];

    /**
     * Searches after a specified pattern and replaces it with provided structure
     *
     * @param  string $pattern Search pattern
     * @param  string $replace Replacement structure
     * @param  string $source  Text to search in
     * @return string Parsed text
     */
    protected function searchAndReplace(string $pattern, string $replace, string $source): string
    {
        while (preg_match($pattern, $source)) {
            $source = preg_replace($pattern, $replace, $source);
        }

        return $source;
    }

    /**
     * Limits the parsers to only those you specify
     *
     * @param  mixed $only parsers
     * @return object BBCodeParser object
     */
    public function only($only = null)
    {
        $only = is_array($only) ? $only : func_get_args();

        $this->parsers = array_intersect_key($this->parsers, array_flip((array) $only));

        return $this;
    }

    /**
     * Removes the parsers you want to exclude
     *
     * @param  mixed $except parsers
     * @return object BBCodeParser object
     */
    public function except($except = null)
    {
        $except = is_array($except) ? $except : func_get_args();

        $this->parsers = array_diff_key($this->parsers, array_flip((array) $except));

        return $this;
    }

    /**
     * Sets the parser pattern and replace.
     * This can be used for new parsers or overwriting existing ones.
     *
     * @param string $name    Parser name
     * @param string $pattern Pattern
     * @param string $replace Replace pattern
     * @param string $content Parsed text pattern
     * @return void
     */
    public function addParser(string $name, string $pattern, string $replace, string $content)
    {
        $this->parsers[$name] = [
            'pattern' => $pattern,
            'replace' => $replace,
            'content' => $content,
        ];
    }

    public function addAlias(string $name, string $alias)
    {
        if (isset($this->parsers[$name])) {
            $this->parsers[$alias] = $this->parsers[$name];
        }
    }
}
