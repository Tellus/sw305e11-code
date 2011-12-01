<script>
// AJAX form handling for replies.
$(document).ready(function()
{
	// alert("Sub-page loaded.");
	$("#replySubmit").click(function(){
		// alert("AJAX'ing onwards.");
		if (verifyForm("#replyBody") == false) return;
		$.post(
			"<?=BaseUrl()?>module/contactbook/add/<?=$message->id?>/",
			{
				parent: "<?=$message->id?>",
				subject: "<?=$message->msgSubject?>",
				child: "<?=$message->msgChildKey?>",
				user: "<?=$userId?>",
				body: $("#replyBody").val()
			},
			onLoaded
		);
	});
	
	// Callback for when a new message has been posted.
	function onLoaded(data)
	{
		if (data == "success")
		{
			// Reload this entire piece of stuff.
			reloadModule();
		}
	}
});
</script>
<div id="messageSubject" style="visibility:hidden;"><?=$message->msgSubject?></div>
<div>
	<h3><?php echo $poster->username; ?> | <?=$message->msgTimestamp?></h3>
	<hr/>
	<table>
	<tr>
		<td style="vertical-align: top;"><?=$message->msgBody?></td>
		<td>
		<?php
		foreach ($images as $image){
			echo "<image class=\"cb-image\" src=\"content/img/" . basename($image) . "\"/><br/>";
		}?>
		</td>
	</tr>
	</table>
	<hr/>
	<?php foreach($replies as $reply) {
		// Get the op.
		$user = GirafUser::getGirafUser($reply->msgUserKey); 
		?>
		<h3><?=$user->username . " | " . $reply->msgTimestamp?></h3>
		<hr/>
		<table>
		<tr>
			<td style="vertical-align: top;"><?=$reply->msgBody?></td>
		</tr>
		</table>
		<hr/>
		<?php } ?>
	<div>
		<form id="messageReplyForm" action="<?=BaseUrl()?>module/contactbook/add/<?=$message->id?>/" method="POST">
			<table>
				<tr>
					<td>Svar fra: </td><td><input type="text" disabled="true" name="userDisplay" value=<?php echo '"' . $userData->username . '"' ?> /></td>
				</tr>
				<tr>
					<td>Besked: </td>
				</tr>
				<tr>
					<td colspan="2"><textarea class="input-textbox" rows="7" name="replyBody" id="replyBody"></textarea></td>
				</tr>
				<tr><td><input id="replySubmit" type="button" value="Send" /></td></tr>
			</table>
		</form>
	</div>
</div>
