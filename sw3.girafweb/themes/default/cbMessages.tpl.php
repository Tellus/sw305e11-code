<!-- cbmessages.php -->
<div id="contactbookupperbar">
</div>

<div id="contactbook">
			
	<div id="newpost">
		<h3><a href="#">New post</a></h3>
		<div>
			<table>
				<tr>
					<td>Oprettet af: </td><!-- Automantisk indsættes brugernavn-->
					<td><input type="text" value="Brugernavn" readonly="readonly"/></td>
				</tr>
				<tr>
					<td>Overskrift: </td><!-- Overskrift, som skal være synlig fra oversigten-->
					<td><input type="text" /></td>
				</tr>
			</table>
			<textarea id="newpostinput">:D</textarea>
				<!-- Lille text editor
				-->
			<input type="file" id="uploadimage"> <!-- Uploader et eller flere billeder til en post -->
		</div>
	</div>
	<!-- Here follows the complete accordian of contactbook messages. -->
	<div id="accordion">
	<?php
	
	require_once(__DIR__ . "../../../include/cbmessage.class.inc");
	
	/**
	 * Get current user.
	 * Get chosen child.
	 * Get all primary messages in reversed order.
	 * Loop basic look.
	 * */

	//$user = users::getCurrentUser();
	$user = 1;

	// $_SESSION["currentChild"] = 1; // For dah tests.
	// $child = $_SESSION["currentChild"];
	$child = $_GET["child"];

	$msgs = ContactbookMessage::getMessages("msgChildKey=$child", null, ContactbookMessage::RETURN_RECORD);
	
	foreach ($msgs as $msg)
	{
		?>
		<h3><a href="#"><?php echo $msg->msgTimestamp; ?> <?php echo $msg->msgSubject; ?><span id="new">New</span></a></h3>
		<div>
			<?php echo $msg->msgBody; ?>
			<input class="readmoreButton" type="button" value="Læs mere"/>
				<!-- Åbner en nyt vindue, som indeholder:
						- Dato
						- Overskrift
						- "Skrevet af"
						- Indhold
						- Billedgalleri /fotostribe
						- Besvar knap
				-->
		</div>
		<?php
	}
	?>
	</div>
	
</div>
<div id="contactbooklowerbar">
</div>
