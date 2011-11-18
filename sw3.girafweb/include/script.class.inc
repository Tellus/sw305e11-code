<?php

// Must-have requires
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/sql_helper.inc");
require_once(__DIR__ . "/settings.func.inc");
require_once(__DIR__ . "/util.func.inc");

/**
 * The GirafScriptParser is responsible for properly parsing girafscript.web
 * markup in template files and replacing them with corresponding responses.
 * Implemented markups are:
 * FUNC|CLASS:METH,PARAM
 * LOOP|VAR:START / END
 * VREF|NAME
 * VDEC|NAME,VAL
 * IF|
 */
class GirafScriptParser
{
    /**
     * String of characters considered reserved for the purposes of GirafScript.
     * Each of the characters in the string will be escaped during serialization
     * and likened procedures.
     */
    const RESERVED_CHARS = ';:}{=!<>';

    /**
     * Path to the template file being parsed.
     */
    public $file_path;
    
    /**
     * String containing the full contents of the template.
     */
    public $file_contents;
    
    /**
     * The current theme for the parser. Matches the directory which is used to
     * read template files.
     */
    public $theme;
    
    /**
     * Array of all markers. This array should never be modified and instead
     * serve as reference data.
     */
    public $markers;
    
    /**
     * Index of the current marker in the array. For now the best way I can
     * trudge through a file.
     */
    protected $currentMarker;
    
    /**
     * Current position in the file we're parsing. Marks the first character of
     * the current marker.
     */
    protected $filePosition;
    
    /**
     * Array of variables used within a single template parse. Currently used by
     * VREF and VDEC, but could have uses elsewhere.
     */
    public $templateVars;
    
    /**
     * Constructs a new parser, prepared to run through a particluar page of
     * a given theme.
     * \param $theme The theme to retrieve a page from. Themes 
     * \param $page Name of the page we want to load. This is the file name,
     * sans the .tpl extension.
     */
    function __construct($page, $theme = "default")
    {
        $root_dir = dirname(__DIR__) . "/themes/";
    
        if (!file_exists("$root_dir/$theme")) die("Requested theme '$theme' is not installed.");
        if (!file_exists("$root_dir/$theme/$page.tpl")) return false;

        $this->loadFile("$root_dir/$theme/$page.tpl");
    }
    
    /**
     * Returns the class' reserved symbols as a requested type.
     * \param $as The type of return value desired. Current valid values are
     * "array" and "string".
     * \trhwos Exception If the passed type request is invalid, a generic
     * Exception is thrown.
     */
    public static function getReservedSymbols($as = "array")
    {
        // '$|;:}{'
        if (strtolower($as) === "array")
        {
            return str_spli(self::RESERVED_CHARS);
        }
        elseif (strtolower($as) === "string")
        {
            return self::RESERVED_CHARS;
        }
        else
        {
            throw new Exception("Bad type requested.");
        }
    }
    
    public function loadFile($file)
    {
        // echo "Loading $file";
        $this->file_path = $file;
        $this->file_contents = file_get_contents($file);    
        $this->getNewMarkers();
        $this->markerIndex = -1;
    }
    
    public function getNewMarkers()
    {
        $this->markers = $this->getMarkers();
        $this->resetMarker();
    }
    
    /**
     * Includes all undefined script commands into the current context.
     * \param $dir Optional. Defines a different directory for script
     * definitions.
     * \return Number of files included.
     */
    public static function includeScriptDefs($dir = null)
    {
        if (!isset($dir)) $dir = __DIR__ . "/script/";
        
        $includes = 0;
        
        $files = scandir($dir);
        foreach ($files as $file)
        {
            if (preg_match("/.+\.php$/", $file) == 1 && basename(__FILE__) != $file)
            {
                require_once($file);
                $includes += 1;
            }
        }
        
        return $includes;
    }
    
    /**
     * Includes a single undefined script command, if available. This is more
     * conservative than includeScriptDefs, which is a raw, get-it-all, kind of
     * thing.
     * \param $name Name of the script definition to include. File extension,
     * path and case is automatic.
     * \return True on success, false on error.
     */
    public static function includeScriptDef($name)
    {
        $name = strtolower($name);
        $path = __DIR__ . "/script/$name.php";
        if (file_exists($path))
        {
            require_once($path);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Uses an old-fashioned search algorithm to find balanced sets of ${ and },
     * taking into account nested occurrences.
     * \return Array of full markers. Embedded markers lie within their parents.
     */
    public function getMarkers()
    {
        return self::getNestedMarkers($this->file_contents);
    }
    
    /**
     * Gets the value associated with a key, or null if the key did not exist.
     */
    public function getVar($key)
    {
        if ((!is_numeric($key) && !is_string($key)) ||
			!array_key_exists($key, $this->templateVars))
            return null;
        else
			return $this->templateVars[$key];
            
    }
    
    /**
     * Sets the value of a particular key.
     */
    public function setVar($key, $val)
    {
        $this->templateVars[$key] = $val;
        return null;
    }
    
    /**
     * Gets a template var and deserializes it if the markers for such a state
     * exists. Functions can call this function to always get PHP objects back
     * from the vars.
     * \sa GirafScriptParser:getDeserializedVar()
     * \sa GirafScriptParser:getSerializedVar()
     * \sa GirafScriptParser:setSerializedVar()
     * \sa GirafScriptParser:setDeserializedVar()
     */
    public function getDeserializedVar($key)
    {
        if (!array_key_exists($key, $this->templateVars)) return null;
        
        $retval = $this->templateVars[$key];
        
        if (self::isSerializedVar($retval)) return unserialize(substr($retval), 2, strlen($retval) - 4);
        else return $retval;
    }
    
    /**
     * Retrieves a variable from the template environment variables and
     * serializes it if it isn't already.
     * \param $key Name of the variable you want to retrieve.
     * \return Variable from the env vars, ensured to be serialized. Null is
     * returned if the key has no value.
     */
    public function setSerializedVar($key, $val)
    {
        if (self::isSerializedVar($val)) $this->setVar($key, $val);
        else $this->setVar($key, "__" . serialize($val) . "__");
    }
    
    /**
     * Sets a value in the template environment, and serializes it if it is not
     * already.
     * \param $key Name of the variable you want to set.
     * \param $val Value you want to store.
     * \return Always returns null.
     */
    public function setDeserializedVar($key, $val)
    {
        if (self::isSerializedVar($val)) $this->setVar($key, unserialize($val));
        else $this->setVar($key, $val);
    }
    
    /**
     * Sets a value in the template environment, and desserializes it if it is
     * in a serialized state.
     * \param $key Name of the variable you want to set.
     * \param $val Value you want to store.
     * \return Always returns null.
     */
    public function getSerializedVar($key)
    {
        $retval = $this->getVar($key);
        
        if (self::isSerializedVar($retval)) return $retval;
        else return unserialize($retval);
    }
    
    /**
     * Checks whether the passed var is likely to be a GirafScript-serialised
     * object and returns accordingly.
     * \param $val The value to be checked.
     * \return True if the value seems to be serialized by GirafScript, false
     * otherwise.
     */
    public static function isSerializedVar($val)
    {
		if (!is_string($val)) return false;
        else return (strrpos($val, "__", 2) !== false && strrpos($val, "__", -2) !== false) ? true : false;
    }
    
    public static function girafDeserialize($val)
    {
		// When *deserializing*, perform the following actions, in order:
		// - check for, then remove, underscores.
		// - unescape using RESERVED_CHARS.
		// - PHP unserialize.
		if (!self::isSerializedVar($val))
			throw new Exception("Very bad serialized var passed.");
			
		$val = substr($val, 2, strlen($val) - 4); // Should be good.
		$val = self::unescapeString($val);
		$val = unserialize($val);
		
		return $val;
	}
    
    /**
     * A GirafScript-specific wrapper for PHP's serialize construct. It performs
     * escaping of the input text.
     * \param $val The object to serialize.
     * \return A serialized version of the input.
     * \note While you can serialize the same value several times, it
     * makes no sense to do so, and will in the end only garble the
     * input, requiring equal amount of deserialization.
     */
    public static function girafSerialize($val)
    {
		// When *serializing*, perform the following actions, in order:
		// - PHP serialize.
		// - escape with RESERVED_CHARS
		// - prepend and append underscores.
		$val = serialize($val);
		$val = self::escapeString($val);
		$val = "__$val__";
		
		return $val;
    }
    
    /**
     * Escapes a string to the GirafScript standard.
     * \param $val String to escape.
     * \return Escaped string.
     * \todo Implement support for escaped escape chars (\).
     * \sa GirafScriptParser::unescapeString()
     */
    public static function escapeString($val)
    {
        $toRepl = self::getReservedSymbols();
        
        $with = array();
        
        foreach($toRepl as $char)
        {
            $with[] = "\\$char";
        }

        return str_replace($toRepl, $with, $val);
    }
    
    /**
     * Unescapes a (previously) escaped GirafScript string.
     * \param $val String to unescape.
     * \return Unescaped string.
     * \todo Implement support for escaped escape chars (\).
     * \sa GirafScriptParser::escapeString()
     */
    public static function unescapeString($val)
    {
        $with = self::getReservedSymbols();
        
        $toRepl = array();
        
        foreach($with as $char)
        {
            $toRepl[] = "\\$char";
        }
        
        return str_replace($toRepl, $with, $val);
    }
    
    /**
     * Retrieves an array of markers embedded within a single full marker. This
     * function recursively retrieves all embedded markers.
     * \param $input The full marker.
     * \param &$arr Array to place embedded markers in.
     * \param $level The level of embedding to process. This is used by the
     * recursive mechanism and should NOT be altered.
     * \return A 2-dimensional array of full markers. Index 0 contains the
     * original marker, index 1 contains the first level of embedding markers
     * and so on. The array is flattened, so you cannot immediately tell which
     * parent levels a given marker may have belonged to, but they are inserted
     * in sequence, so replacing occurrences from the bottom up while following
     * an increasing index should bring you through them all in sequence.
     * \note It is recommended to use getEmbeddedMarkers instead. It wraps this
     * function in a simpler interface.
     */
    public static function getEmbeddedMarkersRec($input, &$arr, $level = 0)
    {
        // echo "Level $level" . PHP_EOL;
    
        // Secure array contents.
        if ($level === 0 && !isset($arr)) $arr = array();
        
        // Ensure the requested level exists.
        if (!array_key_exists($level, $arr)) $arr[$level] = array();
        
        $position = 0;
        
        while(false !== $subset = self::getBalancedText($input, '${', '}', $position+2, true))
        {
            // var_dump($subset);
            self::getEmbeddedMarkersRec($subset, $arr, $level+1);
            $position = strpos($input, $subset, $position) + strlen($subset);
        }
        
        // var_dump($subset);
        
        if ($level > 0)
        {
            $input = '${' . $input . '}'; // In layman's terms, this is a "hack".
        }
        
        $arr[$level][] = $input;
        return $arr;
    }
    
    /**
     * Flattens an array from getEmbeddedMarkers resulting in a one-dimensional
     * array that describes the proper order of execution for the full,
     * original marker.
     * \param $embedArray The array from getEmbeddedMarkers to process.
     * \param $sort_order How you want the data returned. MARKER_ORDER_SET will
     * create an array where lower-level markers are grouped right before their
     * parent, while MARKER_ORDER_LEVEL will simply flatten the array, backwards
     * to front.
     * \return A one-dimensional array formatted as specified by $sort_order.
     */
    public static function getMarkerExecutionOrder($embedArray, $sort_order)
    {
        if ($sort_order == MARKER_ORDER_LEVEL)
        {
            $new_array = array();
            
            // A simple bottom-up approach is to reverese the array and traverse
            // it.
            $embedArray = array_reverse($embedArray);
            
            foreach($embedArray as $levelArray)
            {
                $levelArray = array_reverse($embedArray);
                foreach ($levelArray as $marker)
                {
                    $new_array[] = $marker;
                }
            }
            
            return $new_array;
        }
        elseif ($sort_order == MARKER_ORDER_SET)
        {
            // How to: Reverse recursive handling of the levels.
            // Where x<y, while handling level x, you constantly look through
            
            /**
             * WHEN RUNNING THE MARKERS FROM THE ARRAY, DO SO IN THIS WAY:
             * FOR EACH LOWER LEVEL YOU PROCESS, REPLACE EVERY INSTANCE OF THE
             * REPLACED MARKER THROUGHOUT THE HIGHER LEVELS UNTIL YOU REACH THE
             * TOP. THIS WAY YOU WILL NOT LOSE SEARCH CAPABILITIES DUE TO 
             * PROCESSED VERSUS UNPROCESSED MARKERS.
             * SO WHEN PROCESSING LEVEL 3, CHANGE ALL MARKERS IN BOTH LEVEL 2
             * AND 1 WHEN PROCESSING A SINGLE MARKER. QED.
             */
        }
        else
        {
            throw new Exception("Invalid sort order passed.");
        }
    }
    
    /**
     * Parses a full marker and returns all markers within.
     * \param $input A full marker (${ to }).
     * \return A 2-dimensional array containing level of embedding on one axis,
     * and an array of embedded full markers on the other.
     * \note Notice that this function puts full markers in each level, without
     * actually running them. This is left up to an as yet unnamed function.
     */
    public static function getEmbeddedMarkers($input)
    {
        $arr = array();
        self::getEmbeddedMarkersRec($input, $arr, 0);
        return $arr;
    }
    
    /**
     * Retrieves any markers embedded within another marker.
     * \param $input The input marker. Should contain starting and ending tags,
     * but the function will disregard them either way.
     * \return Array of nested markers, or false.
     * \note Call this function recursively to retrieve the nested markers of
     * nested markers.
     * \todo This needs better implementation. Either it only does nesting, or
     * it has a flawed model for deriving embedded markers.
     */
    public static function getNestedMarkers($input, $returnAs = 'array')
    {
        // Global file position. Used to search progressively through the file.
        $position = 0;
        // Start two characters ahead if a marker was passed.
        if (substr($input, 0, 2) == '${') $position = 2;
        $last_char = substr($input, strlen($input)-1, 1);
        // Stop one character earlier if a marker was passed.
        if ($last_char == '}') $input = substr($input, 0, strlen($input)-1);
        
        // Array of full markers found.
        $markers = array();
        
        // Counter to keep track of nesting level.
        $level = 0;
        
        // For each marker, these denote start and ending positions for substrings.
        $marker_start = $marker_end = 0;
        
        $level = 0;
        
        $done = false;
        
        // echo "Starting run-through." . PHP_EOL;
        while (!$done && $position < strlen($input) - 1)
        {
            // We seek only for ${ if no nesting is done.
            if ($level <= 0)
            {
                $level = 0;
                $marker_start = strpos($input, '${', $position);
                // echo "($position) No current marker. Seeking... $marker_start" . PHP_EOL;
                if (!$marker_start) $done = true;
                else
                {
                    $position = $marker_start + 2;
                    $level += 1;
                    // echo "($position) Fragment: " . substr($this->file_contents, $marker_start, 5) . PHP_EOL;
                }
            }
            else
            {
                $start = strpos($input, '${', $position);
                $end = strpos($input, '}', $position);
                // if (!$start) echo "No start found" . PHP_EOL;
                // if (!$end) echo "No end found" . PHP_EOL;
                if ($start != false && $start < $end)
                {
                    // Start tag is closer than end tag. Increase nest.
                    $level += 1;
                    // echo "($position) Increasing level to $level" . PHP_EOL;
                    $position = $start + 2;
                    // echo "($position) Fragment: " . substr($this->file_contents, $position-2, 5) . PHP_EOL;
                }
                else
                {
                    // End tag is closer. Reduce level.
                    $level -= 1;
                    // echo "Decreasing level to $level" . PHP_EOL;
                    $position = $end + 1;
                    $marker_end = $end;
                    // echo "($position) Fragment: " . substr($this->file_contents, $position-5, 5) . PHP_EOL;
                }
                
                if ($level == 0)
                {
                    $new_marker = substr($input, $marker_start, $marker_end-$marker_start+1);
                    // echo "Adding finished marker '$new_marker' to list." . PHP_EOL;
                    if ($returnAs == 'array') $markers[] = array("marker" => $new_marker, "start" => $marker_start, "end" => $marker_end);
                    elseif ($returnAs == 'string') $markers[] = $new_marker;
                }
            }
        }
        return $markers;
    }
    
    /**
     * Converts a template marker in the form ${class:method:param,param,...} to an
     * array that contains the same information, more readily workable.
     * \param $input The complete marker to interpret. <b>Must not</b> contain
     * other markers.
     * \return Array containing the strings of the marker in syntax order. False
     * is returned if the marker is invalidally typed.
     * \note I'm not 100% on the implementation. I fear it may return bad
     * restults on some input (I just don't know which input).
     */
    public static function parseMarker($input)
    {
        // echo "Parsing $input." . PHP_EOL;
        // First, get the command.
        $cmd_start = 2;
        $cmd_end = strpos($input, "|");
        if (!$cmd_end) // Assume atomic
        {
            $cmd_end = strlen($input) - 1;
        }
        $cmd = substr($input, 2, $cmd_end - $cmd_start);
        
        $input = substr($input, $cmd_end+1);
        $input = substr($input, 0, strlen($input)-1);
        
        // The rest can non-recursively be determined by regexing.

        // This basic regex pattern retrieves all regular characters between
        // irregular characters. More simply: alphanumerics, not curly braces.
        // $marker_reg = '/(.+)(?:[:,]{0,1})/';
        // $marker_reg = '/([A-Za-z0-9=!<>]+)/';
        // This newest regex will match any alphanumeric character, and any of
        // the listed special characters if they are escaped.
        $marker_reg = '/((\w|\\[' . self::RESERVED_CHARS . '])+)/';
        
        $matches = array();
        $matches[0] = $cmd;
        $hits = preg_match_all($marker_reg, $input, $matchesb);//, PREG_SET_ORDER);
        
        $matches = array_merge($matches, $matchesb[1]);

        return $matches;
    }
    
    /**
     * Retrieves and invokes the script handler for each marker found in the
     * input, in correct order.
     * 
     * \sa GirafScriptParser::parseMarker()
     */
    public function runMarker($marker)
    {
        // Quick secure.
        if (is_array($marker)) $marker = $marker["marker"];

        if (substr($marker, 0, 2) != '${' ||  substr($marker, strlen($marker)-1, 1) != '}')
            throw new Exception("Invalid marker passed.");
        
        // Get all embedded markers.
        $marks = self::getEmbeddedMarkers($marker);
        
        // Atomic?
        if(count($marks) == 1)
        {
            // Just run it if atomic.
            $marker_parsed = $this->parseMarker($marker);
            $handler = $this->getScriptHandler($marker_parsed[0]);
            if ($handler === false) return false;
            else
            {
                // var_dump($marker);
                // echo "<br/>";
                $handler->setMarker($marker);
                try
                {
                    $toReturn = $handler->invokeNoReplace();
                    return $toReturn;
                }
                catch (GirafScriptException $e)
                {
                    // return "ERROR!";
                    return $e;
                }
            }
        }
        else
        {
            // Run embedded markers.
            // 
            return $this->runEmbeddedMarkers($marks);
        }
    }
    
    /**
     * Sibling function to runMarker, this function handles the embedded
     * scenario. The added function overhead is to make the code more readable.
     * \return Array with two indices. 0: the original entire marker, 1: the
     * new content for that marker.
     */
    public function runEmbeddedMarkers($marks)
    {
        // Now, start from the bottom of the array and work through each marker
        // and level in sequence, each time replacing one occurrence in every
        // higher level.
        
        $marks = array_reverse($marks); // Simplest way of starting at the bottom.
        
        // echo "Running entire mark group:<br/>";
        // var_dump($marks);

        foreach ($marks as $index=>&$level)
        {
            foreach ($level as &$marker)
            {
                // echo "Running embedded marker $marker<br/>" . PHP_EOL;
                $marker_parsed = $this->parseMarker($marker);
                $handler = $this->getScriptHandler($marker_parsed[0]);
                if ($handler === false)
                {
                    throw new Exception("Error handling for missing markers is also missing!");
                    self::recursiveReplace($marks, $marker, "BAD", 1);
                }
                else
                {
                    try
                    {
                        $parsed_marker = $this->parseMarker($marker);
                        $handler->setMarker($marker);
                        $result = $handler->invokeNoReplace();
                        // echo "Replacing $marker with $result throughout.";
                        self::recursiveReplace($marks, $marker, $result, 1);
                    }
                    catch (GirafScriptException $e)
                    {
                        // echo "Replacing $marker with 'ERROR!' throughout.";
                        self::recursiveReplace($marks, $marker, "ERROR!", 1);
                        break 2;
                    }
                }
            }
        }
        
        $marks = array_reverse($marks);
        
        return $marks[0][0];
    }
    
    /**
     * Replaces a specific string throughout an array up to a specific number
     * of times within each sub-array.
     * \param $haystack The 2-dimensional array (array of array of strings) that
     * contains the strings we want replaced.
     * \param $find The string to seek out.
     * \param $replace The replacement for the string sought out.
     * \param $count Optional, the number of times to replace on each array index.
     * \return The modified $haystack
     */
    public static function recursiveReplace(&$haystack, $find, $replace, $count = 1)
    {
        // For each sub-array...
        foreach ($haystack as &$single)
        {   // For each string in that sub-array...
            foreach ($single as &$string)
            {   // Up to 
                if (strpos($string, $find) !== false)
                    $string = str_replace($find, $replace, $string, $count);
            }
        }
    }
    
    /**
     * Retrieves the script handling class for a requested command.
     * \param $cmd The command (FUNC, LOOP, etc) to get a handler for. String.
     * \return An instance of the handler class if it exists, false otherwise.
     */
    public function getScriptHandler($cmd)
    {
        if (is_array($cmd))
        {
            // var_dump($this->parseMarker($cmd));
            die();
            $cmd = $this->parseMarker($cmd);
            $cmd = $cmd["marker"];
        }
        
        // echo "Retrieving $cmd." . PHP_EOL;
        // By convention, all script classes are lower-case. To try and
        // segregate them from the rest of the code, we keep them in their own
        // namespace.
        $cmdclass = "\\Giraf\\Script\\" . $cmd;
        // If the class has not yet been defined, include it. This is sorta our
        // own manual autoloader.
        if (!class_exists($cmdclass)) $this->includeScriptDef($cmd);
        if (!class_exists($cmdclass)) return false;
        
        // Use reflection to get clas info.
        $refl = new ReflectionClass($cmdclass);
        
        // Create a new subclass instance.
        $newU = $refl->newInstance($this);
        
        return $newU;
    }
    
    /**
     * Seeks out a balanced body of text from a string, given start and end
     * marks (which will be balanced) and an optional offset.
     * \param $input The string to search in.
     * \param $start The starting marker. Start and end markers will be balanced.
     * \param $end The ending marker. Start and end markers will be balanced.
     * \param $offset Optional offset to start at.
     * \param $trim If true, the marks will be removed from the start and end of the body.
     * \return The full body of text between the two marks. The marks themselves
     * will be removed if trim was passed as true.
     */
    public static function getBalancedText($input, $startMark, $endMark, $offset = 0, $trim = false)
    {
        // Global file position. Used to search progressively through the file.
        // Modified by the offset.
        if ($offset > strlen($input)) return false;
        if (strpos($input, $startMark, $offset) === false ||
            strpos($input, $endMark, $offset) === false ) return false;
        $position = strpos($input, $startMark, $offset);

        // Counter to keep track of nesting level.
        $level = 0;
        
        // These denote the starting points of the two most outer markers.
        $body_start = $body_end = $position;
        
        // Saves a lot of overhead.
        $input_len = strlen($input);
        
        $done = false;
        
        
        // echo "Starting run-through." . PHP_EOL;
        while (!$done && $position < $input_len - 1)
        {
            // Find the starting position of a possible nesting point.
            $start = strpos($input, $startMark, $position);
            // Find the position of the next ending mark.
            $end = strpos($input, $endMark, $position);
            // if (!$start) echo "No start found" . PHP_EOL;
            // if (!$end) echo "No end found" . PHP_EOL;

            // Start tag is closer than end tag. Increase nesting.
            if ($start != false && $start < $end)
            {
                $level++;
                // echo "($position) Increasing level to $level" . PHP_EOL;
                $position = $start + strlen($start);
                // echo "($position) Fragment: " . substr($input, $position-2, 5) . PHP_EOL;
            }
            else
            {
                // End tag is closer. Reduce level.
                $level--;
                // echo "Decreasing level to $level" . PHP_EOL;
                $position = $end + strlen($endMark);
                // echo "($position) Fragment: " . substr($input, $position-strlen($endMark), strlen($endMark)) . PHP_EOL;
            }
            
            // If we're at square one, we're done.
            if ($level <= 0)
            {
                // $new_marker = substr($input, $body_start, $position + strlen($endMark));
                // If we end up at level 0 again, we're done. Fully. Badam. Return the substring.
                $done = true;
            }
        }
        $ret = substr($input, $body_start, $position - $body_start);
        if ($trim)
        {
            $ret = substr($ret, strlen($startMark));
            $ret = substr($ret, 0, strlen($ret) - strlen($endMark));
        }
        return $ret;
    }
    
    /**
     * The returns the next marker in the array of markers, non-nested version.
     * \return Marker string that can be parsed by parseMarker, or null if end
     * of array was reached.
     */
    public function getNextMarker()
    {
        $this->currentMarker++;
        if($this->currentMarker > count($this->markers)) return false;
        else return $this->getCurrentMarker();
    }
    
    /**
     * Retrieves the current marker in the marker array without moving the
     * pointer.
     */
    public function getCurrentMarker()
    {
        if($this->currentMarker < 0 || $this->currentMarker > count($this->markers) - 1) return false;
        return $this->markers[$this->currentMarker];
    }
    
    /**
     * Retrieves the current index of the marker array.
     */
    public function getMarkerIndex()
    {
        return $this->currentMarker;
    }
    
    /**
     * Moves the marker pointer one step backwards and returns the value found.
     */
    public function getPrevMarker()
    {
        $this->currentMarker--;
        if($this->currentMarker<0) return false;
        else return $this->getCurrentMarker();
    }
    
    /**
     * Moves the marker pointer to the first index of the marker array and
     * returns its value.
     */
    public function getFirstMarker()
    {
        $this->currentMarker = 0;
        return $this->getCurrentMarker();
    }
    
    /**
     * Moves the marker pointer to the last index of the marker array and
     * returns its value.
     */
    public function getLastMarker()
    {
        $this->currentMarker = count($this->markers) - 1;
        return $this->getCurrentMarker();
    }
    
    /**
     * Resets the array marker.
     */
    public function resetMarker()
    {
        $this->currentMarker = -1;
    }
    
    /**
     * Fully parses an input template and returns the newly generated content.
     * \param Optionally the string to parse. If omitted, file_contents is used.
     * \return The modified file in its entirety, ripe for printage.
     */
    public function parseTemplate(&$file = null)
    {
        if (!isset($file)) $file = $this->file_contents;
    
        $markers = $this->getMarkers($file);
        
        foreach ($markers as $marker)
        {
            $newText = $this->runMarker($marker);

            // $this->file_contents = util::str_replace_once($marker["marker"], $newText, $this->file_contents);
            
            $file = util::str_replace_once($marker["marker"], $newText, $file);
        }
        
        // At this point, $this->file_contents is hopefully fully replaced.
        return $file;
    }
}

?>
