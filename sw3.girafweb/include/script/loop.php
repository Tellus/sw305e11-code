<?php

namespace Giraf\Script;

require_once("base.php");

/**
 * 
 */
class sloop extends GirafScriptCommand
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
    public function invoke()
    {
        $iter = $this->iter;
        $name = $this->iterName;
        if ($this->iter == null)
        {
            $this->replaceMarker("The array '$name' is not defined.");
            return;
        }
        
        // Remember the index so we can properly reverse every time we re-loop.
        $startIndex = $this->parent->getMarkerIndex();
        
        // The prefab is the base template for the contents between the two loop
        // markers.
        $prefab = $this->parent->file_contents;
        $curMark = $this->parent->getCurrentMarker();
        $prefab = substr($prefab, $curMark["start"], strpos($prefab, '${ENDLOOP}') - strlen('${ENDLOOP}'));
        
        var_dump($prefab);
        throw new \Exception();
        
        while ('${ENDLOOP}' != $marker = $this->parent->getNextMarker())
        {
        
        }
    
        // echo "FUNC invoked" . PHP_EOL;
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
                $this->replaceMarker("The method '" . $this->method . "' does not exist in the class.");
            }
        }
        else
        {
            $this->replaceMarker("Requested class '" . $this->cmdClass . "' does not exist.");
        }
    }
    
    public function getParameters()
    {
        // We just need one parameter. An array.
        $this->iter = $this->parent->getVar($this->marker[1]);
        $this->iterName = $this->marker[1];
    }
}

?>
