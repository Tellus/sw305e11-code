<?php

namespace Giraf\Script;

require_once("base.php");

/**
 * 
 */
class vdec extends GirafScriptCommand
{
    public $varName;
    public $varValue;

    /**
     * Calls the func marker command that will, in turn, attempt to call the
     * true backend function and return its value.
     * \note Recall that commands are atomic. They will not themselves handle
     * embedded markers. That must be handled by the parser itself.
     * \param A marker string or array as defined in GirafScriptCommand.
     * \return Whatever the hell the called function feels like returning.
     * \sa GirafScriptCommand::invoke()
     */
    public function invokeNoReplace(&$unused)
    {/*
        echo "VDEC invoked " . $this->marker . PHP_EOL;
        var_dump($this->marker);*/
        $this->parent->setVar($this->varName, $this->varValue);
        return ""; // Remove the marker.
    }
    
    public function getParameters()
    {
        $this->varName = $this->marker[1];
        $this->varValue = $this->marker[2];
    }
}

?>
