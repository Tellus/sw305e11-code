<?php

require_once(__DIR__ . "/include/html.func.inc");
require_once(__DIR__ . "/include/script.class.inc");

/**
 * Base index file. Takes several post or session arguments to determin what
 * page and in what state to load. The template files determine which parts of
 * the controllers should be invoked by index.php.
 */ 

if (!isset($_GET["page"])) die("No page was requested.");

$parser = new GirafScriptParser($_GET["page"]);
// $parser = new GirafScriptParser("test");

echo $parser->parseTemplate(null, true);

?>
