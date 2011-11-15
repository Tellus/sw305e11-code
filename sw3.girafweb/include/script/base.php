<?php

namespace Giraf\Script;

/**
 * Base class for all girafscript.php commands. This is to satisfy the state
 * design pattern, and because I likes it!
 */
abstract class GirafScriptCommand
{
    /**
     * Reference to the parser instance that create this command instance.
     */
    public $parent;

    /**
     * The passed input marker. Among other things.
     */
    public $marker;

    /**
     * Array of the parameters in the marker. Depending on script, this is
     * usually starting from index 1 or 2 in the input marker.
     */
    public $parameters;

    /**
     * Inherited by subclasses. Performs the actions required by the command.
     * The marker should have been set beforehand with setMarker().
     * \param $body The body of text that the script should work on. This text
     * should for single-tag markers simply be the marker itself. For dual-tags
     * it should be the entire body of text including the full start and end
     * tags.
     * \return The full body, modified as needed by the script.
     */
    public abstract function invoke();
    
    /**
     * Sets the current marker in use for the script instance.
     * \param $marker Either a full marker string or a marker already parsed
     * through GirafScriptParser::parseMarker().
     * \sa GirafScriptParser::parseMarker()
     */
    public function setMarker($marker)
    {
        if (is_array($marker))
        {
            $this->marker = $marker;
        }
        elseif (is_a($marker, "string"))
        {
            $this->marker = $this->parent->parseMarker($marker);
        }
        else throw new \Exception("Invalid marker type '$marker' passed.");
        
        // Draw out the parameters.
        $this->getParameters();
    }
    
    /**
     * Replaces the marker's text in the template file. This is a simple
     * baseline method for all atomic markers.
     * \param $repl The replacement text to insert.
     * \param $toRepl Optionally override the marker search with text of your
     * own choosing. This effectively makes replaceMarker a simple proxy call
     * for str_replace().
     */
    public function replaceMarker($repl, $toRepl = null)
    {
        if (!isset($toRepl))
        {
            $toRepl = $this->parent->getCurrentMarker();
            $toRepl = $toRepl["marker"];
        }
        // echo "Replacing '$toRepl' with '$repl'.<br/>" . PHP_EOL;
        $number = 1;
        // echo "BEFORE" . PHP_EOL;
        // var_dump($this->parent->file_contents);
        $this->parent->file_contents = str_replace($toRepl, $repl, $this->parent->file_contents, $number);
        // echo "AFTER" . PHP_EOL;
        // var_dump($this->parent->file_contents);
    }
    
    /**
     * Should construct and return the end marker for the current instance.
     * May not do anything depending on implementation.
     * \return A full marker string of the tag that should end this marker tag.
     * if no end tags exist for the marker, false should be returned.
     * \sa Giraf\Script\loop:getEndMarker()
     * \sa Giraf\Script\if:getEndMarker()
     */
    public function getEndMarker()
    {
        return false;
    }
    
    /**
     * Should extract parameters from $this->marker as necessary and place them
     * in $this->parameters.
     */
    protected abstract function getParameters();
    
    /**
     * Creates a new instance of the command. Requires its parent.
     * \param $parent Parser that owns this command. Will be used for data
     * manipulation.
     */
    function __construct($parent)
    {
        $this->parent = $parent;
    }
}

?>
