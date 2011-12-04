<?php

require_once(INCDIR . "apps/contactbook/contactbook.class.inc");
require_once(INCDIR . "image.class.inc");

/**
 * Sub-controller for the Contactbook application. By definition cannot
 * be called without an accompanying action *and* at least one
 * parameter.
 * */
class Contactbook extends GirafController
{
	public function index()
	{
		die("Contactbook module cannot be called directly!");
	}
	
	/**
	 * We allow fallback to perform a default-type action with the
	 * action parameter if it is a numeric character. In this case we
	 * request a default action from the subcontroller tied to a child
	 * id.
	 * */
	public function fallback($action, $params = array())
	{
		if (is_numeric($action)) $this->_default($action, $params);
		elseif (strtolower($action) == "list") $this->_list($params);
		else parent::fallback($action, $params);
	}
	
	/**
	 * This function is called by fallback if the action was given as
	 * an integer. In this case, it is assumed that the calling client
	 * requests the results of an index action tied to a specific child.
	 * */
	public function _default($action, $params = array())
	{
		// echo "Echoing default data for $action.";
		// The default action is _list :D
		
		// We fake-construct a new path for the action to use.
		// I'm thinking we can smooth this process with CURRENTCONTROLLER
		// constants and optional _default action callbacks.
		$path = "/contactbook/list/$action";
		$this->_list(GetAssocPath($path));
	}
	
	public function show($params = array())
	{
		$data = array();
		
		if (!isset($s)) $s = GirafSession::getSession();

		$userId = $s->getCurrentUser();
		$data["userId"] = $userId;

		if ($userId == null || $userId == false)
		{
			die("Page cannot be used without logging in.");
		}

		$userData = GirafUser::getGirafUser($userId);
		$data["userData"] = $userData;

		// By definition the parameter right after the action is the
		// message id. That index is param0.
		if (!isset($params["param0"])) die("Ingen besked ID efterspurgt!");
		else $msgId = $params["param0"];

		// Get message data.
		$messageData = ContactbookMessage::getMessage($msgId);
		// Mark it read. We loaded it, yeh?
		$messageData->setRead($userId);
		
		$data["message"] = $messageData;
		// Image data.
		$data["images"] = $messageData->getImages();

		// Get OP data.
		$data["poster"] = GirafUser::getGirafUser($messageData->msgUserKey);

		// Get current user.
		if (!isset($s)) $s = GirafSession::getSession();
		$userId = $s->getCurrentUser();

		// Get replies.
		$data["replies"] = $messageData->getReplies();
		
		$this->view("apps/contactbook/show", $data);
	}
	
	/**
	 * I wants me a friggin' list function actually NAMED list!
	 * However, "list" is a reserved word in PHP, so we shadow the
	 * name and call the function through the fallback function
	 * instead.
	 * */
	public function _list($params = array())
	{
		$data = array();
		
		if (!isset($s)) $s = GirafSession::getSession();

		$userId = $s->getCurrentUser();
		$userData = GirafUser::getGirafUser($userId);

		$data["childId"] = $params["param0"];
		$cond = 'msgChildKey=' . $data["childId"] . ' AND msgParentKey IS NULL';

		$data["messages"] = ContactbookMessage::getMessages($cond, null, ContactbookMessage::RETURN_RECORD);
		$data["username"] = $userData->username;
		$data["userId"] = $userId;
		
		$this->view("apps/contactbook/list.php", $data);
	}
	
	/**
	 * Creates a new contactbook message.
	 * */
	public function add($params = array())
	{
		if (!isset($_POST["user"], $_POST["child"], $_POST["subject"], $_POST["body"]))
		{
			echo "failure";
			return;
		}
		$msgId = ContactbookMessage::createNewMessage(	$_POST["user"],
														$_POST["child"],
														$_POST["subject"],
														$_POST["body"],
														isset($_POST["parent"]) ? $_POST["parent"] : null);
		
		// Handle uploaded images. We do this now because we didn't know the
		// message id beforehand.
		foreach ($_FILES as $file)
		{
			// $path = __DIR__ . "/../../content/img/" . $file["name"];
			$path = BASEDIR . "img/" . $file["name"];
			// var_dump($path, $file["tmp_name"]);
			$res = move_uploaded_file($file["tmp_name"], $path);
			// Save to database.
			GirafImage::createMessageImage($path, $msgId, "Billedtekst mangler.");
		}		
		
		echo "success";
	}
}

?>
