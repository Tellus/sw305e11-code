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
    public function invokeNoReplace(&$unused)
    {
        return 'WHEN is not yet implemented.';
    }
    
    public function getParameters()
    {
        $this->condition = $this->marker[1];
    }
}

?>
