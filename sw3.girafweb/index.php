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

// $markers = $parser->getMarkers();

$file = file_get_contents("./themes/default/login.tpl");

$marker = '/\$\{(\w+)\|(?:(?<=)(\w+))+\}/';

echo "Hits: " . preg_match_all($marker, $file, $matches, PREG_SET_ORDER) . PHP_EOL;

var_dump($matches);

$commands = GirafScriptParser::parseMarker('${FUNC|CLASS:METHOD,PARAM,PARAM}');

$eval = $commands[1] . "::" . $commands[2] . "();";
var_dump($eval);
eval($eval);

// This should fail.
eval("echo(\"Hello\");");

?>
