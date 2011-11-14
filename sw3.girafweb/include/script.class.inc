<?php

// Must-have requires
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/sql_helper.inc");
require_once(__DIR__ . "/settings.func.inc");

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
     * Path to the template file being parsed.
     */
    public $file_path;
    
    /**
     * String containing the full contents of the template.
     */
    private $file_contents;
    
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
     * Returns the current index of the marker array.
     * \return $markerIndex from within the class.
     */
    public function getMarkerIndex()
    {
        return $this->$filePosition;
    }
    
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
    
    public function loadFile($file)
    {
        // echo "Loading $file";
        $this->file_path = $file;
        $this->file_contents = file_get_contents($file);    
        $this->markers = $this->getMarkers();
        $this->markerIndex = -1;
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
     * An out-of-memory alternative to getNedstedMarkers and getMarkers. It uses
     * a safer approach to marker parsing that should hopefully reduce the
     * entropy we experience from the regular full-file mechanism.
     * \param $file Path to the file we want markers from.
     * \return  An array of full markers. They are thus still in an embedded
     * state if they contain something from one another.
     * \warning Currently I cannot guarantee that markers on the same line are
     * read in proper sequence. There's an issue with the position iteration.
     */
    public static function getMarkersFromFile($file)
    {
        $toRead = file($file, FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);
        
        $ret = array();
        foreach($toRead as $line)
        {
            $new_markers = self::getNestedMarkers($input);
            if (!$new_markers) continue;
            
            
        }
    }
    
    /**
     * Retrieves any markers embedded within another marker.
     * \param $input The input marker. Should contain starting and ending tags,
     * but the function will disregard them either way.
     * \return Array of nested markers, or false.
     * \note Call this function recursively to retrieve the nested markers of
     * nested markers.
     */
    public static function getNestedMarkers($input)
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
                    $markers[] = array("marker" => $new_marker, "start" => $marker_start, "end" => $marker_end);
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
        $marker_reg = '/(\w+)(?:[:,]{0,1})/';
        
        $matches = array();
        $matches[0] = $cmd;
        $hits = preg_match_all($marker_reg, $input, $matchesb);//, PREG_SET_ORDER);
        
        $matches = array_merge($matches, $matchesb[1]);
        
        return $matches;
    }
    
    /**
     * Determines necessary script being requested, ensures it is defined, calls
     * it and returns the script's return value. This should be called by
     * the parser for each marker block that needs parsing.
     * \param $marker A marker array prepared by parseMarker().
     * \sa GirafScriptParser::parseMarker()
     */
    public function runMarker($marker)
    {
        // Calls the class method with all the parmeters in sequence.
        $hndl = $this->getScriptHandler($marker[0]);
        call_user_func_array(array($newU, "invoke"), $marker);
    }
    
    /**
     * Retrieves the script handling class for a requested command.
     * \param $cmd The command (FUNC, LOOP, etc) to get a handler for. String.
     * \return An instance of the handler class if it exists, false otherwise.
     */
    public function getScriptHandler($cmd)
    {
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
     * Fully parses an input template and returns the newly generated content.
     * \return The modified file in its entirety, ripe for printage.
     */
    public function parseTemplate($file = null)
    {
        // var_dump($this->markers);
        
        $pos = 0;
        
        // While we have markers to process.
        $this->currentMarker = -1;
        while($marker = $this->getNextMarker())
        {
            // echo "Handling " . $marker["marker"];
            // Find the next marker.
            // $pos = strpos($file, $marker, $pos + strlen($marker));
            $pos = $marker["start"];
            // Get an array version of the marker.
            $markerArray = $this->parseMarker($marker["marker"]);
            // Find a proper script handler instance (or create a new one).
            $handler = $this->getScriptHandler($markerArray[0]);

            $body_length = "";
            
            if ($handler != false)
            {
                // Give the instance a marker.
                $handler->setMarker($markerArray);
                
                echo "Calling $markerArray[0]";
                // Invoke it. It has a parent reference and can work it as necessary.
                $handler->invoke();   
            }
            elseif ($handler == false)
            {
                $errText = "<span class='girafscriptError'>The marker command '" . $marker["marker"] . "' is unknown</span>";
                // echo "Replacing '" . $marker["marker"] . "' with '$errText'";
                $this->file_contents = str_replace($marker["marker"], $errText, $this->file_contents);
                continue;
            }
            
            /*
            if ($endMarker = $handler->getEndMarker()) // If the handler class defines an end marker, we need more text.
            {
                // The length is the amount of characters from the first character
                // in the start marker to the final character in the end marker.
                // $body_length = strpos($this->file_contents, $endMarker) + strlen($endMarker) - strpos($this->file_contents, $marker);
                for ($i = $this->currentMarker+1; $i < count($this->markers); $i++)
                {
                    $finalMarker = $this->markers[$i];
                    if ($finalMarker["marker"] === $endMarker)
                    {
                        echo "Matching end marker found.<br/>";
                        $body_length = $finalMarker["end"] - $marker["start"];
                    }
                }
                $this->file_contents = str_replace(substr($file, $marker["start"], $finalMarker["end"] - $marker["start"]), $handler->invoke(), $this->file_contents);
            }
            else
            {
                $body_length = strlen($marker);
                $this->file_contents = str_replace($marker, $this->runMarker($markerArray), $this->file_contents);
            }*/
        }
        
        // At this point, $this->file_contents is hopefully fully replaced.
        return $this->file_contents;
    }
} 
?>
