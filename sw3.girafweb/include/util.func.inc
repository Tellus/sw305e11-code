<?php

/**
 * Class with really miscellaneous nice-to-haves.
 */
abstract class util
{
    /**
     * Determines whether the input has a trailing slash. If not, adds one.
     * \param $input The input string you want a trailing character on.
     * \return A string that is either identical to $input or has a trailing character.
     */
    public static function trailingSlash($input)
    {
        if (preg_match("/\/$/", $input)) return $input;
        else return $input . "/";
    }
    
    /**
     * Single-replace version of standard PHP str_replace.
     * \param $needle What we're looking for.
     * \param $replace What to replace with.
     * \param $haystack Where to look.
     * \return $haystack, modified.
     * \sa str_replace()
     */
    public static function str_replace_once($needle , $replace , $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) return $haystack;
        else return substr_replace($haystack, $replace, $pos, strlen($needle));
    }
    
    /**
     * Creates an array of integers.
     * \param $from Starting number (inclusive).
     * \param $to Ending number (inclusive, if possible).
     * \param $step Interval between numbers. Optional.
     * \return Array of integers.
     */
    public static function getArrayOfIntegers($from, $to, $step = 1)
    {
        $out = array();
        while ($to > $from)
        {
            $out[] = $to;
            $from += $step;
        }
        return $out;
    }
    
    private static $safeSyntaxMsg;
    
    /**
     * A Giraf implementation of the deprecated php_check_syntax
     * function. Will use the CLI version of PHP to check a file for
     * syntax errors.
     * \param $file Full path to the file in question.
     * \param $run If true, the file will also be executed within the
     * CGI context (that is, included).
     * \return True if the file is OK, false otherwise.
     */
    public static function checkFileSyntax($file, $run = false)
    {
		// We cheat a little. PHP doesn't find any syntax errors in the
		// file "." (current dir). We use that output to determine
		// safeness of other files.
		if (!isset(self::$safeSyntaxMsg))
		{
			self::$safeSyntaxMsg = `php -l .`;
			self::$safeSyntaxMsg = substr(self::$safeSyntaxMsg, 0, strlen(self::$safeSyntaxMsg)-2);
		}
		$output = `php -l $file`;
		
		if (strpos($output, self::$safeSyntaxMsg) !== false)
		{
			if ($run) include_once($file);
			return true;
		}
		else
		{
			return false;
		}
	}
}

/**
 * Returns the base url for the site. Note this is the path to the
 * index file, not the document root.
 * \param Optional path to append to the url.
 * \return String URL to the site.
 * */
function BaseUrl($path = "")
{
	// return "http://giraf.homestead.dk/$path";
	return SITE_BASE_URL;
}

/**
 * Finds and executes an action on a controller.
 * \param $cont The controller to call.
 * \param $act The action to run.
 * \param $param Parameters to pass.
 * */
function CallController($cont, $act = "index", $param = null)
{
	// Reduce to lower-case, as per convention.
	$cont = strtolower($cont);
	
	// Attempt to find the requested controller.
	$controller_path = BASEDIR . "/controllers/$cont.php";
	if (!file_exists($controller_path)) throw new Exception("The controller '$cont' does not exist.");

	// If the controller exists, include it, then determine action.
	require($controller_path);

	// We're assuming the controller exists now, but let's just check.
	if (!class_exists($cont)) throw new Exception ("The controller '$cont' does not exist.");

	// Use reflection to get clas info.
	$refl = new ReflectionClass($cont);

	// Create a new subclass instance.
	$newController = $refl->newInstance();

	if (method_exists($newController, $act)) $newController->$act($param);
	else $newController->fallback($act, $param);
}

/**
 * Base function for CallController, useful for core classes that need
 * more control.
 * */
function _call_controller_internal($controller, $path, $action, $parameters)
{	
	// Reduce to lower-case, as per convention.
	$cont = strtolower($controller);
	
	// Append .php if not already there.
	if (substr($cont, strlen($cont)-4) != ".php") $cont .= ".php";
	
	// Check for file existance.
	$full_path = "$path/$controller.php";
	if (!file_exists($full_path)) throw new Exception("The controller '$cont' does not exist.");

	// If the controller exists, include it, then determine action.
	require($full_path);

	// We're assuming the controller exists now, but let's just check.
	$cont = substr($cont, 0, strlen($cont)-4);
	if (!class_exists($cont)) throw new Exception ("The controller '$cont' does not exist.");

	// Use reflection to get clas info.
	$refl = new ReflectionClass($cont);

	// Create a new subclass instance.
	$newController = $refl->newInstance();

	if (method_exists($newController, $action)) $newController->$action($parameters);
	else $newController->fallback($action, $parameters);
}

/**
 * Redirects the client connection to a different URL.
 * \param $url Either a protocol-prefixed address (http://something) or
 * a string denoting a site path (controller/action/param). The redirect
 * header will be instantly sent and the output buffer cleared.
 * */
function Redirect($url)
{
	$pos = strpos($url, "://");
	if ($pos !== false && strpos($url, "://") <= 4)
	{
		// If it seems likely that such a pattern is part of a protocol
		// URL, don't touch it. OTHERWISE, prepend base url.
	}
	else
	{
		if ($url[0] == '/') $url = substr($url, 1);
		
		$url = BaseUrl() . $url;
	}
	
	header("Location: $url");
	ob_clean();
}

/**
 * Runs through a path variable and returns a more manageable command
 * array.
 * \param $path Either a string or an array to run through.
 * \return Standardised associative array. Index 0 becomes the
 * controller, 1 the action and all following becomes parameters.
 * */
function GetAssocPath($path)
{
	if (is_string($path))
	{
		$path = explode('/', $path);
		if (count($path) > 0) array_shift($path);
	}
	elseif (is_array($path))
	{
		// No probs.
	}
	else
	{
		throw new Exception("Invalid type of path objet passed, '" . get_class($path) . "'.");
	}
	
	// Go through all entries. The first and second entry will be 
	// controller and action, respectively, while all following will be
	// parameters.
	$newPath = array();
	$count = 0;
	foreach ($path as $entry)
	{
		switch ($count)
		{
			case 0:
			{
				// $path["controller"] = $entry;
				$newPath["controller"] = $entry;
				break;
			}
			case 1:
			{
				// $path["action"] = $entry;
				$newPath["action"] = $entry;
				break;
			}
			default:
			{
				// $path["param".($count-2)] = $entry;
				$newPath["param".($count-2)] = $entry;
				break;
			}
		}
		$count++;
	}
	
	// return $path;
	return $newPath;
}

function GetPath($asAssoc = false)
{
	$path = explode('/', $_SERVER['PATH_INFO']);
	// I've found that the first element is always a null string, so now
	// it's fuckin' gone.
	if (count($path) > 0) array_shift($path);
	
	if ($asAssoc) return GetAssocPath($path);
	else return $path;
}

?>
