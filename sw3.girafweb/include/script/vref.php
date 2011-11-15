<?php

namespace Giraf\Script;

require_once("base.php");

/**
 * 
 */
class vref extends GirafScriptCommand
{
    /**
     * Name of the variable we want to retrieve.
     */
    public $varName;

    public $varIndex;

    function __construct($parent)
    {
        parent::__construct($parent);
        unset($this->varIndex); // Make sure to unset, for our sake. Mmm, sake.
    }

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
        // echo "vref invoked" . PHP_EOL;
        // We need to have the class initialised.
        
        // Branch. Get, post or regular?
        $name = $this->varName;
        
        $text = "";
        
        // Do we have an index?
        if (isset($this->varIndex))
        {
            // Determine source.
            if (strtolower($name) === "get")
            {
                if (isset($_GET[$this->varIndex])) $text = $_GET[$this->varIndex];
            }
            elseif (strtolower($name) === "post")
            {
                if (isset($_POST[$this->varIndex])) $text = $_POST[$this->varIndex];
            }
            else
            {
                // Retrieves the var from the parser. If it's an array, we can
                // retrieve something by index. If _not_ we attempt to read
                // object data.
                $data = $this->parent->getVar($name);
                if (is_array($data))
                {
                    // regular array. Regular aquisition.
                    $text = $data[$this->varIndex];
                }
                else
                {
                    // Assume regular object. Attempting get.
                    $ind = $this->varIndex;
                    $text = $data->$ind;
                }
            }
        }
        else
        {
            // If it's no form of collection, just get the var value.
            $text = $this->parent->getVar($this->varName);
        }
        
        if ($text == null) $this->replaceMarker("NULL");
        else $this->replaceMarker($text);
    }
    
    public function getParameters()
    {
        $this->varName = $this->marker[1];
        
        // If a parameter is given to the variable name, its an index. Notably,
        // this is used for arrays (including _POST and _GET) and object
        // members.
        if (array_key_exists(2, $this->marker)) $this->varIndex = $this->marker[2];
    }
}

?>
