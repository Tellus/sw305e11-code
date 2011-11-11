<?php

require_once(__DIR__ . "/include/html.func.inc");

/**
 * Base index file. Takes several post or session arguments to determin what
 * page and in what state to load. The template files determine which parts of
 * the controllers should be invoked by index.php.
 */ 

// echo "<html>";

$marker_match = '/\${[A-Za-z0-9:]+}/';

function getTplFunc($input)
{
    // Expects ${tEXt:for:FUNCTION}
    // Outputs text_for_function
    $input = substr($input, 2, strlen($input)-3);
    
    $input = str_replace(":", "_", $input);
    
    $input = strtolower($input);
    
    return $input;
}

if (!isset($_GET["page"])) die("No page was requested.");

// Time to parse a template!
$template = file(__DIR__ . "/themes/default/" . $_GET["page"] . ".tpl", FILE_IGNORE_NEW_LINES);

// var_dump($template);

// echo "Loading template";

// We run through the template, line by line. Any matches on the marker template
// will be instantly handled by 
$output = "<html>";

$file = file_get_contents(__DIR__ . "/themes/default/". $_GET["page"] . ".tpl");

while(preg_match($marker_match, $file, $match) > 0)
{
    $cmd = getTplFunc($match[0]);
    if (method_exists("html", $cmd))
    {   
        html::$cmd();
        $replacements = 1;
    }
    else
    {
        echo '<span class="__girafweberrortext">The command ' . $cmd . ' is unknown.</span>';
    }
    // Finally, remove the marker.
    $file = str_replace($match, "", $file, $replacements);
}

$file .= "</html>";

echo $file;

?>
