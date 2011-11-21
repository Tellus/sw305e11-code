<?php

require_once("theme.conf");

require_once(INCLUDE_PATH . "cbmessage.class.inc");
require_once(INCLUDE_PATH . "session.class.inc");

if (!isset($s)) $s = GirafSession::getSession();

$userId = $s->getCurrentUser();
$userData = GirafUser::getGirafUser($userId);

?>

<!-- cbmessages.php -->
<div id="contactbookupperbar">
</div>

<div id="contactbook">
			
	<div id="newpost">
		<h3><a href="#">Nyt indlæg</a></h3>
		<div>
			<form name="newMessageForm" action="index.php?page=cbAddMessage" method="POST">
			<input type="hidden" name="child" value=<?php echo '"' . $_GET["child"] . '"'; ?> id="newMessageChildId" />
			<table>
				<tr>
					<td>Oprettet af: </td><!-- Automantisk indsættes brugernavn-->
					<td><input type="text" name="user" value=<?php echo '"' . $userData->username . '"'; ?> readonly="readonly"/></td>
				</tr>
				<tr>
					<td>Overskrift: </td><!-- Overskrift, som skal være synlig fra oversigten-->
					<td><input name="subject" type="text" /></td>
				</tr>
				<tr>
					<td colspan="2"><textarea class="newpostinput" name="body">:D</textarea></td>
				</tr>
			<tr><td><input type="file" id="uploadimage"></td></tr>
			<tr><td><input type="submit" value="Send" /></td></tr>
			</table>
			</form>
		</div>
	</div>
	<!-- Here follows the complete accordian of contactbook messages. -->
	<div id="accordion">
	<?php
	
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
	?>
	</div>	
</div>
<div id="contactbooklowerbar">
</div>

<div id="readMessageDialog" title="%subject%">
<div id="messageDialogContents">This will write out some neat contactbook information!</div>
</div>
