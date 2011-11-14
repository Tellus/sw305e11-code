<?php

require_once(__DIR__ . "/include/html.func.inc");
require_once(__DIR__ . "/include/script.class.inc");

/**
 * Base index file. Takes several post or session arguments to determin what
 * page and in what state to load. The template files determine which parts of
 * the controllers should be invoked by index.php.
 */ 

// if (!isset($_GET["page"])) die("No page was requested.");

$parser = new GirafScriptParser("login");

/*
var_dump($parser->markers);

echo "Trying to get markers" . PHP_EOL;
while (false != $mrk = $parser->getNextMarker())
{
    echo "MARKER:" . PHP_EOL;
    var_dump($mrk);
}
echo "Done dumping markers" . PHP_EOL;
var_dump($parser);
*/

echo $parser->parseTemplate();

?>
