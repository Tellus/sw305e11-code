<?php

require_once(INCDIR . "apps/contactbook.class.inc";

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
	
	public function fallback($action, $params = array())
	{
		if (strtolower($action) == "list") $this->_list($params);
		else parent::fallback($action, $params);
	}
	
	public function show($params = array())
	{
		echo "Woot! Showing contactbook stuffy!";
	}
	
	/**
	 * I wants me a friggin' list function actually NAMED list!
	 * However, "list" is a reserved word in PHP, so we shadow the
	 * name and call the function through the fallback function
	 * instead.
	 * */
	public function _list($params = array())
	{
		if (!isset($s)) $s = GirafSession::getSession();

		$userId = $s->getCurrentUser();
		$userData = GirafUser::getGirafUser($userId);
		
		/**
		 * Get current user.
		 * Get chosen child.
		 * Get all primary messages in reversed order.
		 * Loop basic look.
		 * */

		// $_SESSION["currentChild"] = 1; // For dah tests.
		// $child = $_SESSION["currentChild"];
		$child = $_GET["child"];

		$cond = "msgChildKey=$child AND msgParentKey IS NULL";
		
		// var_dump($child, $cond);

		// Retrieve all PRIME message.
		$msgs = ContactbookMessage::getMessages($cond, null, ContactbookMessage::RETURN_RECORD);
		
		if(count($msgs) > 0)
		{
			foreach ($msgs as $msg)
			{
				?>
				<h3><a href="#"><?php echo $msg->msgTimestamp; ?> <?php echo $msg->msgSubject; ?><span id="new">New</span></a></h3>
				<div>
					<?php echo $msg->msgBody; ?>
					<input id=<?php echo '"message-' . $msg->id . '"'; ?> class="readmoreButton" type="button" value="Læs mere"/>
				</div>
				<?php
			}
		}
		else
		{
			?>
			<div>Der er ingen beskeder i barnets kontaktbog. Opret eventuelt en besked.</div>
			<?php
		}
	}
	
	/**
	 * Creates a new contactbook message.
	 * Parameters are expected to be found in $_POST as follows:
	 * childId => integer id of the child the message belongs to.
	 * groupId => if this is used *instead* of childId, all children
	 * in that group will receive the message.
	 * msgId => if the message is a reply to a message, use this
	 * instead.
	 * msgSubject => Subject of the message. If omitted for a reply,
	 * a standardised reply subject is used.
	 * msgBody => Text-part of the message.
	 * imageX (X being an integer) => an image resource in the POST
	 * data.
	 * Invokes the show action affecting the 
	 * */
	public function add($params = array())
	{
		
	}
}

?>
