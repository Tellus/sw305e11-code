<?php

/**
 * This file contains various error handling mechanisms and classes.
 */

/**
 * 
 */
class GirafScriptException extends Exception
{
    function __construct($msg)
    {
        parent::__construct($msg);
    }
}
 
?>
