<?php

require_once("theme.conf");
require_once(INCLUDE_PATH . "cbmessage.class.inc");
require_once(INCLUDE_PATH . "users.func.inc");
require_once(INCLUDE_PATH . "image.class.inc");

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

// Handle uploaded images. We do this now because we didn't know the
// message id beforehand.
foreach ($_FILES as $file)
{
	$path = __DIR__ . "/../../content/img/" . $file["name"];
	// var_dump($file["tmp_name"]);
	$res = move_uploaded_file($file["tmp_name"], $path);
	// if ($res != true) die("Error during save.");
	// Save to database.
	GirafImage::createMessageImage($path, $newMess, "Billedtekst mangler.");
}

// Redirect back to the main page. Messy, but functional.
header("Location: index.php?page=main");

?>
