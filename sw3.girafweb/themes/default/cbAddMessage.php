<?php

require_once("theme.conf");
require_once(INCLUDE_PATH . "cbmessage.class.inc");
require_once(INCLUDE_PATH . "users.func.inc");

// CB add message.

// echo "You called me!";

// Get proper user.
$userId = $_POST["user"];
if (is_numeric($userId))
{
	// Yay.
}
elseif (is_string($userId))
{
	$userId = users::getUserId($_POST["user"]);
}
else
{
	die ("Very bad user type passed.");
}

if (!isset($_POST["parent"]))
{
	$newMess = ContactbookMessage::createNewMessage($userId,
													$_POST["child"],
													$_POST["subject"],
													$_POST["body"]);
}
else
{
	$newMess = ContactbookMessage::createNewMessage($userId,
													$_POST["child"],
													$_POST["subject"],
													$_POST["body"],
													$_POST["parent"]);
}

if ($newMess == false)
{
	die("Error occurred creating message.");
}

header("Location: index.php?page=main");

?>
