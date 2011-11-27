<?php

// Set some header data.
header('Content-Type: text/html; charset=utf-8');

// Prepare some path constants for the script.
define("INCDIR", __DIR__ . "/include/");
define("BASEDIR", __DIR__ . "/");

// Demand mandatory files.
require_once(INCDIR . "util.func.inc");
require_once(INCDIR . "session.class.inc");
// require_once(INCDIR . "parser.class.inc");

// Get a session, make sure the user is logged in.
$s = GirafSession::getSession();

$userId = $s->getCurrentUser();

$isLoggedIn = $userId != null;

$path = GetPath(true);

// Head straight to login controller if requested, or force it if the
// user is not logged in.
if(!isset($path["controller"]) || strtolower($path["controller"]) == "login" || !$isLoggedIn)
{
	$controller = "login";
}
else // If logged in, free controller access is allowed.
{
	// $controller = ucfirst($path["controller"]);
	$controller = $path["controller"];
}

// Extract action, if any.
$action = isset($path["action"]) ? $path["action"] : "index";

// Start output buffering. By effectively delaying the printing of any
// contents, we can allow for redirection mechanisms without wasting
// data transference time to the browser, only headers.
ob_start();

// Finally, call the controller that is to be ... called.
CallController($controller, $action, $path);

// Flush the buffer if we reach this point.
ob_end_flush();

?>
