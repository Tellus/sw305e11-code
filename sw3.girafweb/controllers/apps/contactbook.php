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
	public function __construct()
	{
		parent::__construct();
		header("Content-type: text/html");
	}
	
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
	 * Retrieves the application's unique ID. As this can vary from one
	 * GIRAFAdmin installation to the next (or even within the same
	 * install), a method to retrieve it is useful.
	 * \return The ID if found, false (BAAAD!) otherwise.
	 **/
	private function getAppId()
	{
		return sql_helper::simpleQuery("SELECT " . GirafApplication::getPrimaryKey() . " FROM " . GirafApplication::getSourceTable() . " WHERE applicationSystemName='contactbook'");
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
		$data["appId"] = $this->getAppId();
		
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
			$tmp_name = $file['tmp_name'];
			$imgFile = fopen($tmp_name, 'r');
			$imgRaw = addslashes(fread($imgFile, filesize($tmp_name)));
			fclose($imgFile);
			
			$sql = "
				INSERT INTO imageResources
				(imgUri, imageMimeType, imageSize, imageData)
					VALUES
				('$path','".$file['type']."',".$file['size'].", '$imgRaw')";
			
			sql_helper::insertQuery($sql);
			$imgId = sql_helper::simpleQuery("SELECT imgId FROM imageResources WHERE imgUri='$path'");
			
			sql_helper::insertQuery("INSERT INTO cbMsgImages (imgKey, msgKey) VALUES ($imgId, $msgId)");
		}		
		
		echo "success";
	}
}

?>
