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
    public function invokeNoReplace(&$changes)
    {
        if (!is_array($this->iter)) throw new BadInputTypeException($this->iter);
     
		if (!is_array($changes)) $changes = array();
        
        // Parent stuff. Shorthands.
        $parent = $this->parent;
        $file = $parent->file_contents;
        $curMarker = $this->full_marker;
        
        // Retrieved the marker area we're supposed to replace. Balancing is
        // not exactly an issue, but it's a great quick function for getting
        // this delimited text.
        // $body = $this->getBalancedText($file, $curMarker, '${ENDLOOP}', 0, true);
        $body = $parent->getBalancedText($file, '${LOOP|', '${ENDLOOP}', strpos($file, $curMarker)-1, false);
        
        $orig_body = $body;
        
        $body = substr($body, strlen($curMarker), strlen($body) - strlen($curMarker) - strlen('${ENDLOOP)'));
        
        $body = str_replace(array('\n', PHP_EOL), "", $body);
        
        // Parse $body by normal parsing rules.
        
        // Special rules apply to variables within loops to make it easier for
        // designers to iterate. In particular, a special variable is made
        // available, akin to the last parameter in a PHP foreach loop. This
        // variable must be updated after each loop to reflect the next entry in
        // the list. The most effective way to do this is to have the LOOP
        // class (this class) run and replace the markers by itself and then
        // return the final output.
        
        $output = "";
        
        // var_dump($this->iter);
        
        // Loop over the list.
        foreach ($this->iter as $loop)
        {
			$parse_body = $body;
			
            // Set the single loop instance into the declared var name for it.
            // Once we run markers in here, they can reference it without issue.
            $parent->setVar($this->iterName, $loop);
            // Body will be recursively replaced.
            
            $parent->parseTemplate($parse_body);
            
            $output = $output . $parse_body;
        }
        
        $to_replace = substr($orig_body, strlen($curMarker));
        
        /*
        echo "<hr/>Final output from loop:<br/>";
        var_dump($output);
        echo "<br/>";
        var_dump($to_replace);
        echo "<br/>";
        echo "<hr/>";
        */
        
        $changes[] = array($to_replace, $output);
                
        return ""; // Remove the loop marker.
    }
    
    public function getParameters()
    {
		// We attempt to discern the nature of the first parameter.
		// Serialized data will be deserialized. Any other data
		// (always string) will be used as env var keys. We will follow
		// the value as a key until an end point is reached or the key
		// loops. This is necessary because we don't know how deep the
		// reference runs. Our test.tpl example has an array (with a 
		// random identifier) referenced by 'numbers' referenced by
		// 'list'. Even reducing it to the lowest possible combination
		// of VREF and LOOP, we have at least one intermediary
		// reference (random -> list).
        
        // Get the value.
        $iter = $this->marker[1];
        
        $p = $this->parent;
        if ($p->isSerializedVar($iter))
        {
			// echo "Recognized serialized data...<br/>";
			// Hopefully array data when deserialized.
			$data = $p->girafDeserialize($iter);
		}
		else
		{
			// echo "Non-serialized string '$iter' encountered. Getting var...<br/>";
			// String
			// This should never loop forever. getVar will return null
			// when the passed value does not reference anything.
			$prev_key = $p->getVar($iter, true);
			/*while ($iter !== null)
			{
				$prev_key = $iter;
				$iter = $p->getVar($iter);
				// echo "Key: $iter<br/>";
			}*/
			
			// When $iter is finally null, $prev_key vill contain the
			// final valid value.
			
			// Deserialize, if necessary.
			if ($p->isSerializedVar($prev_key)) $prev_key = $p->girafDeserialize($prev_key);
			
			// At this point, we should have a healthy array or invalid data.
			
			$data = $prev_key;
		}
		
		if (!is_array($data))
			throw new BadInputTypeException($data);
        
        // var_dump($data);

		$this->iter = $data;

        $this->iterName = $this->marker[1];
    }
}

?>
