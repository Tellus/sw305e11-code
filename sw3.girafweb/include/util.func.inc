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
}

?>
