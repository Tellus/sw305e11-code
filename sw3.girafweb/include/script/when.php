<?php

namespace Giraf\Script;

require_once("base.php");

/**
 * 
 */
class when extends GirafScriptCommand
{
    public $condition;
    public $whenText;
	public $sub;
	
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
		$this->whenText = $this->parent->getBalancedText($this->parent->file_contents, '${WHEN|', '${ENDWHEN}', 0, false);
		// Remove the first condition. It's unparsed.
		$length = strlen($this->parent->getBalancedText($this->whenText, '${', '}'));
		$this->sub = substr($this->whenText, $length);
		
		var_dump($length); 
		var_dump($this->sub); ?> <br /> <?php
		
		//var_dump($this->whenText);
		//var_dump($this->full_marker);
		
		$evalCond = "return " . $this->condition . ";";
		
		if (eval($evalCond))
		{
			// The condition holds.
		}
		else
		{
			// The condition does not hold.
		}
		
		
        return "";
    }
    
    public function getParameters()
    {
        $this->condition = $this->marker[1];
    }
}

?>
