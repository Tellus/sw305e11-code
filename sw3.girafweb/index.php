<?php

require_once(__DIR__ . "/include/html.func.inc");

/**
 * Base index file. Takes several post or session arguments to determin what
 * page and in what state to load. The template files determine which parts of
 * the controllers should be invoked by index.php.
 */ 

// echo "<html>";

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

foreach($template as $line)
{
    // echo "Testing $line<br/>";
    if(preg_match('/\${[A-Za-z0-9:]+}/', $line) > 0)
    {
        // echo "Template on $line<br/>";
        $cmd = getTplFunc($line);
        // echo "Command : " . $cmd;
        if (method_exists("html", $cmd))
        {
            html::$cmd();
        }
        else
        {
            echo("ERROR: the template command $cmd is unknown<br/>");
        }
    }
}

// echo "</html>";

?>
