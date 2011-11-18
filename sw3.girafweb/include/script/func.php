<?php

namespace Giraf\Script;

require_once("base.php");

class UnknownClassException extends \GirafScriptException
{
    function __construct($class)
    {
        parent::__construct("The class '$class' is undefined or unknown.");
    }
}

class UnknownFuncException extends \GirafScriptException
{
    function __construct($func, $class)
    {
        parent::__construct("The function '$func' was not found in the class '$class'.");
    }
}

/**
 * The FUNC marker command runs static class methods and saves the return value
 * in the parser's environment variables with a special value. Using FUNC by
 * itself in a template should not make sense. Instead, use it to produce arrays
 * for LOOP or values for VREF and VDEC.
 * In practicality, this is done in two different steps by FUNC. FUNC will
 * register the return value with setVar() with a random variable name. That
 * name will be returned within a VREF marker by func.
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
     * \return Func will store the returned data in a randomly generated
     * key and instead return the key name. This allows markers such as
     * loop to retrieve proper object data instead of toString pieces.
     * \sa GirafScriptCommand::invoke()
     */    
    public function invokeNoReplace()
    {
        // echo "FUNC invoked" . PHP_EOL;
        // We need to have the class initialised.
        if (class_exists($this->cmdClass))
        {
            if (method_exists($this->cmdClass, $this->method))
            {
                // We need to return the modified body. Since the body *is* the
                // marker, we simply return the result of the function call.
                $tmpname = uniqid();
                $retval = call_user_func_array(array($this->cmdClass, $this->method), $this->parameters);
                
                $this->parent->setVar($tmpname, $retval);
                
                // var_dump($tmpname, $retval);
                
                return $tmpname;
            }
            else
            {
                throw new UnknownFuncException($this->method, $this->cmdClass);
            }
        }
        else
        {
            throw new UnknownClassException($this->cmdClass);
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
