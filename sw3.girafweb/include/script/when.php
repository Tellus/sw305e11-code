<?php

namespace Giraf\Script;

require_once("base.php");

/**
 * 
 */
class when extends GirafScriptCommand
{
    public $condition;
    public $lvalue;
    public $rvalue;

    /**
     * Calls the func marker command that will, in turn, attempt to call the
     * true backend function and return its value.
     * \note Recall that commands are atomic. They will not themselves handle
     * embedded markers. That must be handled by the parser itself.
     * \param A marker string or array as defined in GirafScriptCommand.
     * \return Whatever the hell the called function feels like returning.
     * \sa GirafScriptCommand::invoke()
     */
    public function invoke()
    {
        // Under all circumstances, find the body of text that should be removed.
        $file = $this->parent->file_contents;
        $mark = $this->parent->getCurrentMarker();
        // The offset *should* be unnecessary becase we work sequencially.
        $oldContent = \GirafScriptParser::getBalancedText($file, $mark["marker"], '${ENDWHEN}', 0, false);

        if (eval("return " . $this->condition . ";"))
        {
            // echo "EXECUTING TRUTH<br/>" . PHP_EOL;
            $newContent = \GirafScriptParser::getBalancedText($file, $mark["marker"], '${ENDWHEN}', 0, true);
        }
        else
        {
            // echo "EXECUTING FALSE";
            $newContent = "";
        }
        $this->replaceMarker($newContent, $oldContent);
        
        // Simple dodge for now. We force the parser to start over after
        // this condition to make it handle the possibly nested instances itself.
        $this->parent->getNewMarkers();
    }
    
    public function getParameters()
    {
        $this->condition = $this->marker[1];
    }
}

?>
