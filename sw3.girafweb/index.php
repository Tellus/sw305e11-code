<?php

// Prepare some path constants for the script.
define("GIRAF_INCLUDE", __DIR__ . "/include/");


// Demand mandatory files.
require_once(GIRAF_INCLUDE . "util.func.inc");
require_once(GIRAF_INCLUDE . "session.class.inc");
require_once(GIRAF_INCLUDE . "script.class.inc");

// Load utility functions. And classes. Given some web discussions,
// it is discouraged to consider this bad code until we actually see
// detrimental performance.
// Update: this procedure has actually prolonged page load with a few
// seconds on a no-load server. Still, for now, let's not worry about
// it until we have more users.
$files = scandir(GIRAF_INCLUDE);
foreach ($files as $file)
{
	if (is_dir(GIRAF_INCLUDE . $file)) continue; // Ignore dirs.
	if (!util::checkFileSyntax(GIRAF_INCLUDE . $file, true))
	{
		// echo "Ignored '" . GIRAF_INCLUDE . $file . "'<br/>";
	}
}

/**
 * Base index file. Takes several post or session arguments to determin what
 * page and in what state to load. The template files determine which parts of
 * the controllers should be invoked by index.php.
 */ 

// We should consider the client platform and pick theme accordingly.
// var_dump($_SERVER["HTTP_USER_AGENT"]);

// Get a session, make sure the user is logged in.
$s = GirafSession::getSession();

$userId = $s->getCurrentUser();

$isLoggedIn = $userId != null;

// if (!isset($_GET["page"]) || ($_GET["page"] != "login" && $isLoggedIn))
if (!$isLoggedIn && $_GET["page"] != "login")
{
	// This is not good. Return the user to the login page with an
	// expiration error.
	// GirafSession::set(errorMsg,"You are not logged in or your session has expired. Please log in again.");
	
	// Redirect. Will create an infinite loop if we aren't careful.
	header("Location: index.php?page=login");
}
elseif ($isLoggedIn && !isset($_GET["page"]))
{
	$_GET["page"] = "main";
}

// Test page existance.
if (false) // Assume theme was not picked. Fallback to default.
{
	// Figure out the current theme and stick to it.
}
else
{
	// Otherwise, go default. This should really reside in the global
	// app settings in case another default theme (not "default") is
	// desired.
	$theme_dir = __DIR__ . "/themes/default/";
}


// $parser = new GirafScriptParser($_GET["page"]);
// $parser = new GirafScriptParser("login");

// echo $parser->parseTemplate(null, true);

// Some header fun.
header('Content-Type: text/html; charset=utf-8');

// New method. Change into the directory to relative references work locally.
include_once($theme_dir . $_GET['page'] . ".php");

?>
