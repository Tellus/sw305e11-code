<!-- File for single message contents. -->
<?php

// Should be handled by index.php, but just for debugging...
require_once($_SERVER["DOCUMENT_ROOT"] . "/dev/include/session.class.inc");
require_once($_SERVER["DOCUMENT_ROOT"] . "/dev/include/user.class.inc");
require_once($_SERVER["DOCUMENT_ROOT"] . "/dev/include/cbmessage.class.inc");

// Prep data

// We only need one thing for the display itself, but we should have
// current user information stored in-session.

$userId = GirafSession::getCurrentUser();

if ($userId == null || $userId == false)
{
	$userId = 1;
}

$userData = GirafUser::getGirafUser($userId);

if (array_key_exists("message", $_GET))
{
	$msgId = $_GET["message"];
}
else
{
	die("Ingen besked ID efterspurgt!");
}

// Get message data.
$messageData = ContactbookMessage::getMessage($msgId);

// Get OP data.
$poster = GirafUser::getGirafUser($messageData->msgUserKey);

// Get replies.
$replies = $messageData->getReplies();

?>
<div id="messageSubject" style="visibility:hidden;"><?php echo $messageData->msgSubject; ?></div>
<div>
	<h3><?php echo $poster->fullname; ?> | <?php echo $messageData->msgTimestamp ?></h3>
	<div>
		<?php echo $messageData->msgBody; ?>
	</div>
	<hr/>
	<?php
	
	// Alright, let's get the replies, too.
	
	foreach($replies as $reply)
	{
		?>
		<div>
			<h3><?php echo $reply->msgSubject; ?></h3>
			<div><?php echo $reply->msgBody; ?></div>
		</div>
		<hr/>
		<?php
	}
	
	?>
	<div>
		<form>
			<table>
				<table>
					<tr>
						<td>Svar fra: </td><td><input type="text" id="username" disabled="true" value="<?php echo $userData->fullname; ?>"/></td>
					</tr>
					<tr>
						<td>Besked: </td>
					</tr>
					<tr>
						<td colspan="2"><textarea id="newMessageBody"></textarea></td>
					</tr>
				</table>
			</table>
		</form>
	</div>
</div>
