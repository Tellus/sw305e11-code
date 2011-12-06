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
	
	$(".cb-image").click(function(){
		var id = event.target.id;
		var src = event.target.src;
		console.debug(event.target.src);
		
		$("#cb-image-box-src").attr("src", src);
		$("#cb-image-box").dialog("open");
		$("#cb-image-box").dialog("option", {position: [25, 25]});
		
		var image = $("#cb-image-box-src");
		console.debug(image);
		
		var newImg = new Image();
		newImg.src = src;
		var height = newImg.height;
		var width = newImg.width;
		// alert ('The image size is '+width+'*'+height);
		
		if (height > 768)
		{
			$("#cb-image-box-src").height("768");
			$("#cb-image-box-src").width("auto");
		}
		else if (width > 1024)
		{
			$("#cb-image-box-src").height("auto");
			$("#cb-image-box-src").width("1024");
		}
		
		$("#cb-image-box").dialog("option", {position: 'center'});
	});
	
	$("#cb-image-box").dialog({
		autoOpen: false,
		buttons:
		{
			Luk: function(){$(this).dialog("close")}
		},
		width: 'auto',
		modal: true
	});
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
			$n = basename($image); ?>
			<img class="cb-image" src="<?=BaseUrl()?>img/<?=$n?>" id="img_<?=$n?>" />
		<?php } ?>
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
					<td>Svar fra: </td><td><input type="text" readonly="readonly"	 name="userDisplay" value=<?php echo '"' . $userData->username . '"' ?> /></td>
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
<div id="cb-image-box">
	<img id="cb-image-box-src" src="." />
</div>
