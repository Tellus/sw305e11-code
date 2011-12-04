<?php

namespace Giraf\Script;

require_once("base.php");

/**
 * 
 */
class when extends GirafScriptCommand
{
    public $condition;
    
    /**
     * Contains all text of the marker.
     */
    public $whenText;
    
    /**
     * Contains all the text of the marker except the first, full marker
     * (the WHEN, part). Used for proxied changes.
     * */
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
    public function invokeNoReplace(&$unused)
    {
		$this->whenText = $this->parent->getBalancedText($this->parent->file_contents, '${WHEN|', '${ENDWHEN}', 0, false);
		// Remove the first condition. It's unparsed.
		$length = $this->parent->getBalancedText($this->whenText, '${', '}');
		$this->sub = substr($this->whenText, strlen($length));
		
		// First thing we do is retrieve the WHEN condition and test it.
		
		self::getCondition($
		
		$evalCond = "return " . $this->condition . ";";
		
		// Retrieve invalid tokens from the condition. Notably, this is
		// unquoted strings.
		
		// Our regex matches all patterns that are NOT inside quotation
		// marks.
		$match = '/(["\w]+)/';
		
		$hits = array();
		$hitReplace = array();
		
		preg_match_all($match, $this->full_marker, $hits);
		
		foreach($hits[0] as $hit)
		{
			if ($hit == null)
			{
				// OK value.
				$hitReplace[] = "null";
			}
			elseif (is_numeric($hit))
			{
				// OK value.
				$hitReplace[] = $hit;
			}
			elseif (substr($hit, 0, 1) == substr($hit, strlen($hit)-1))
			{
				// OK value.
				$hitReplace[] = $hit;
			}
			else
			{
				// We assume some form of variable.
				$endVar = $this->parent->getVar($hit, true);
				if ($endVar == null)
				{
					$hitReplace[] = "null";
				}
				else
				{
					$hitReplace[] = "\"$endVar\"";
				}
			}
		}
		$con = str_replace($hits[0], $hitReplace, $this->full_marker);
		
		$pipe_pos = strpos($con, '|') + 1;
		$con = substr($con, $pipe_pos, strlen($con) - $pipe_pos - 1);
		
		$truth = eval("return ($con);");
		
		/*echo "<hr/>";
		var_dump(("null" == null && "1" == 1) || "Myass" == null);
		echo "<hr/>";
		
		
		var_dump($con);
		var_dump($truth);
		
		die();*/
		
		$con_string = "return ($con);";
		
		if (eval($con_string) === true)
		{
			echo "Writing truth value<br/>";
			// The condition holds.
		}
		else
		{
			echo "Writing false value.<br/>";
			// The condition does not hold.
		}
		
		
        return "";
    }
    
    /**
     * Takes a complete WHEN condition (everything from | to }) and
     * attempts to properly discern its truth value.
     * \param The full condition.
     * \return True if the condition evaluates to true, false otherwise.
     */
    public static function condValue($input)
    {
		
	}
    
    public function getParameters()
    {
        $this->condition = $this->marker[1];
        //var_dump($this->marker);
        //var_dump($this->full_marker);
    }
}

?>
