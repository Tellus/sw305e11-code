<?php

namespace Giraf\Script;

require_once("base.php");

class BadInputTypeException extends \GirafScriptException
{
    function __construct($input)
    {
        parent::__construct("The type '" . gettype($input) . "' is not a valid array input.");
    }
}

/**
 * 
 */
class loop extends GirafScriptCommand
{
    public $iter;
    public $iterName;

    /**
     * Calls the func marker command that will, in turn, attempt to call the
     * true backend function and return its value.
     * \note Recall that commands are atomic. They will not themselves handle
     * embedded markers. That must be handled by the parser itself.
     * \param A marker string or array as defined in GirafScriptCommand.
     * \return Whatever the hell the called function feels like returning.
     * \sa GirafScriptCommand::invoke()
     */
    public function invokeNoReplace()
    {
        if (!is_array($this->iter)) throw new BadInputTypeException($this->iter);
        
        // Parent stuff. Shorthands.
        $parent = $this->parent;
        $file = $parent>file_contents;
        $curMarker = $this>full_marker;
        
        // Retrieved the marker area we're supposed to replace. Balancing is
        // not exactly an issue, but it's a great quick function for getting
        // this delimited text.
        $body = $this->getBalancedText($file, $curMarker, '${ENDLOOP}', 0, true);
        
        // Parse $body by normal parsing rules.
        
        // Special rules apply to variables within loops to make it easier for
        // designers to iterate. In particular, a special variable is made
        // available, akin to the last parameter in a PHP foreach loop. This
        // variable must be updated after each loop to reflect the next entry in
        // the list. The most effective way to do this is to have the LOOP
        // class (this class) run and replace the markers by itself and then
        // return the final output.
        
        // Loop over the list.
        foreach ($this->iter as $loop)
        {
            // Set the single loop instance into the declared var name for it.
            // Once we run markers in here, they can reference it without issue.
            $parent->setVar($this->iterName, $loop);
            // Body will be recursively replaced.
            $parent->parseTemplate($body);
        }
    }
    
    public function getParameters()
    {
        // The first loop parameter will *always* be considered a variable.
        $iter = $this->marker[1];

        while (!is_array($iter))
        {
            // Recursively follow the variable references until we find a proper
            // array.
            $prev_iter = $iter;
            $iter = $this->parent->getVar($iter);
            if ($iter === $prev_iter || $iter === null)
            {
                $iter = null;
                break;
            }
        }
        
        var_dump($iter);

        $this->iterName = $this->marker[1];
    }
}

?>
