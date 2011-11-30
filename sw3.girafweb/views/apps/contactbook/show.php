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
	<?php
	
	// Alright, let's get the replies, too.
	
	foreach($replies as $reply)
	{
		// Get the op.
		$user = GirafUser::getGirafUser($reply->msgUserKey); 
		
		?>
		<div>
			<!-- h3><?php echo $reply->msgSubject; ?></h3 -->
			<h3><?php echo $user->username . " | " . $reply->msgTimestamp; ?></h3>
			<hr/>
			<table>
			<tr>
		<td style="vertical-align: top;"><?php echo $reply->msgBody; ?></td>
		<td>
		<?php
		
		// For each image, show it!
		
		$imgs = $reply->getImages();
		
		foreach ($imgs as $image)
		{
			echo "<image class=\"cb-image\" src=\"content/img/" . basename($image) . "\"/><br/>";
		}
		
		?>
		</td>
	</tr>
	</table>
		<hr/>
		<?php
	}
	
	?>
	<div>
		<form id="messageReplyForm" action="index.php?page=cbAddMessage" method="POST">
		<input type="hidden" name="subject" value=<?="\"SV: " . $message->msgSubject . '"'?> />
		<input type="hidden" name="parent" value=<?='"' . $message->id . '"'?> />
		<input type="hidden" name="child" value=<?='"' . $message->msgChildKey . '"'?> />
		<input type="hidden" name="user" value=<?='"' . $userId . '"'?> />
			<table>
				<tr>
					<td>Svar fra: </td><td><input type="text" disabled="true" name="userDisplay" value=<?php echo '"' . $userData->username . '"' ?> /></td>
				</tr>
				<tr>
					<td>Besked: </td>
				</tr>
				<tr>
					<td colspan="2"><textarea name="body" id="newMessageBody"></textarea></td>
				</tr>
				<tr><td><input type="submit" value="Send" /></td></tr>
			</table>
		</form>
	</div>
</div>
