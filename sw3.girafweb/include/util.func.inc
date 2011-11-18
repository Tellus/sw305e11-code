<?php

/**
 * Class with really miscellaneous nice-to-haves.
 */
abstract class util
{
    /**
     * Determines whether the input has a trailing slash. If not, adds one.
     * \param $input The input string you want a trailing character on.
     * \return A string that is either identical to $input or has a trailing character.
     */
    public static function trailingSlash($input)
    {
        if (preg_match("/\/$/", $input)) return $input;
        else return $input . "/";
    }
    
    /**
     * Single-replace version of standard PHP str_replace.
     * \param $needle What we're looking for.
     * \param $replace What to replace with.
     * \param $haystack Where to look.
     * \return $haystack, modified.
     * \sa str_replace()
     */
    public static function str_replace_once($needle , $replace , $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) return $haystack;
        else return substr_replace($haystack, $replace, $pos, strlen($needle));
    }
    
    /**
     * Creates an array of integers.
     * \param $from Starting number (inclusive).
     * \param $to Ending number (inclusive, if possible).
     * \param $step Interval between numbers. Optional.
     * \return Array of integers.
     */
    public static function getArrayOfIntegers($from, $to, $step = 1)
    {
        $out = array();
        while ($to > $from)
        {
            $out[] = $to;
            $from += $step;
        }
        return $out;
    }
}

?>
