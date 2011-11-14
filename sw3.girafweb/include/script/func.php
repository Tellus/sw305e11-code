<?php

namespace Giraf\Script;

require_once("base.php");

/**
 * 
 */
class func extends GirafScriptCommand
{
    public $cmdClass;
    public $method;

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
        echo "FUNC invoked" . PHP_EOL;
        // We need to have the class initialised.
        if (class_exists($this->cmdClass))
        {
            echo "Requested class does not exist." . PHP_EOL;
            $method = $input[2]; // We need to have this method available on the class.
            if (method_exists($this->cmdClass, $this->method))
            {
                // We need to return the modified body. Since the body *is* the
                // marker, we simply return the result of the function call.
                return call_user_func_array(array($this->cmdClass, $this->method), $this->parameters);
            }
            else
            {
                throw new Exception("Requested method '" . $this->method . "' does not exist.");
            }
        }
        else
        {
            throw new Exception("Requested class '" . $this->cmdClass . "' does not exist.");
        }
    }
    
    public function getParameters()
    {
        $this->cmdClass = $this->marker[1];
        $this->method = $this->marker[2];
        $this->parameters = array_slice($this->marker, 3);
    }
}

?>
