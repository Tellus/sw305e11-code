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
     * Resource pointer to the file.
     */
    public $file_resource;
    
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
        $curdir = getcwd();
        chdir(__DIR__);
        chdir("..");
        if (!file_exists("./themes")) die("Themes directory not present in " . getcwd() . ".");
        if (!file_exists("./themes/$theme")) die("Requested theme '$theme' is not installed.");
        if (!file_exists("./themes/$theme/$page.tpl")) return false;
        
        $this->loadFile("./themes/default/$page.tpl");
        
        chdir($curdir);
    }
    
    public function loadFile($file)
    {
        $this->file_contents = file_get_contents($file);    
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
        if (substr($input, 0, 2) == '${') $position = 2;
        $last_char = substr($input, strlen($input)-1, 1);
        if ($last_char == '}') $input = substr($input, 0, strlen($input)-1);
        
        // Array of full markers found.
        $markers = array();
        $num_markers = 0;
        
        // Counter to keep track of nesting level.
        $level = 0;
        
        // For each marker, these denote start and ending positions for substrings.
        $marker_start = $marker_end = 0;
        
        $level = 0;
        
        $done = false;
        
        // echo "Starting run-through." . PHP_EOL;
        while (!$done && $position < strlen($input) - 1)
        {
            // We only seek ${ if no nesting is done.
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
                    $markers[$num_markers] = $new_marker;
                    $num_markers += 1;
                }
            }
        }
        return $num_markers > 0 ? $markers : false;
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
        // First, get the command.
        $cmd_start = 2;
        $cmd_end = strpos($input, "|");
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
        
        var_dump($matches);
        
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
        // By convention, all script classes are lower-case. To try and
        // segregate them from the rest of the code, we keep them in their own
        // namespace.
        $cmd = strtolower($marker[0]);
        $cmdclass = "\\Giraf\\Script\\" . $cmd;
        // If the class has not yet been defined, include it. This is sorta our
        // own manual autoloader.
        if (!class_exists($cmdclass)) $this->includeScriptDef($cmd);
        
        // Use reflection to get clas info.
        $refl = new ReflectionClass($cmdclass);
        
        // Create a new subclass instance.
        $newU = $refl->newInstance($this);
        
        // Calls the class method with all the parmeters in sequence.
        call_user_func_array(array($newU, "invoke"), $marker);
    }
    
    /**
     * The returns the next marker in the list of markers, non-nested version.
     * \return Marker string that can be parsed by parseMarker, or null if end
     * of array was reached.
     */
    public function getNextMarker()
    {
        $this->currentMarker += 1;
        if (count($this->markers) > $this->currentMarker += 1;) return false;
        else return $this->$markers[$this->currentMarker += 1;];
    }
    
    /**
     * Fully parses an input template and returns the newly generated content.
     * \param $file Optionally a new file to parse. If omitted, the file parsed
     * during initialisation is used.
     * \return The modified file in its entirety, ripe for printage.
     */
    public function parseTemplate($file = null)
    {
        if(!isset($file)) $file = $this->file_path;
        
        $this->loadFile($file);
        
        $this->markers = $this->getMarkers();
        $this->markerIndex = -1;
        
        $pos = 0;
        
        // While we have markers to process.
        while($marker = $this->getNextMarker())
        {
            // Find the next marker.
            $pos = strpos($file, $marker, $pos + strlen($marker));
            // Get an array version of the marker.
            $markerArray = $this->parseMarker($marker);
            // Find a proper script handler instance (or create a new one).
            $handler = $this->getScriptHandler($markerArray[0]);
            // Give the instance a marker.
            $handler->setMarker($markerArray);
            
            $body_length = "";
            if ($endMarker = $handler->getEndMarker())
            {
                // The length is the amount of characters from the first character
                // in the start marker to the final character in the end marker.
                $body_length = strpos($this->file_contents, $endMarker) + strlen($endMarker) - strpos($this->file_contents, $marker);
            }
            else
            {
                $body_length = strlen($marker);
                str_replace($marker, $handler->invoke()
            }
            str_replace(substr($file, strpos($marker), $body_length), $handler->invoke, $this->file_contents);
        }
        
        // At this point, $this->file_contents is hopefully fully replaced.
    }
} 
?>
