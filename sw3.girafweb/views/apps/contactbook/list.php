<script>
		// Re-initialize some jquery stuff.
		// We need a really neat way of adding a new script element
		// to the document when loading modules. We need something
		// simple to easily add javascript to a loaded module. Easy
		// in principle.
		$("#accordion").accordion({
			collapsible: true,
			//active: false
		});

		$("#newpost").accordion({
			collapsible: true,
			active: false
		});
		
		$(".readmoreButton").button().click(function(){
			// alert("Getting more for more.");
			// $("#readMessageDialog").dialog("open");
			// Load data.
			var msgId = event.target.id;
			msgId = msgId.substring(msgId.indexOf("-")+1);
			// $.get("themes/default/cbMessage.tpl.php", { message : msgId }, function(contents, ign, ignToo)
			$.get("<?=BaseUrl()?>module/contactbook/show/"+msgId, {}, function(contents)
			{
				$("#messageDialogContents").html(contents);
				$("#readMessageDialog").dialog("open");
				// alert("Changing " + $("#readMessageDialog").attr("title") + " to " + $("#messageSubject").html());
				$("#readMessageDialog").dialog('option', 'title', $("#messageSubject").html());
			});
		});
		
		$( "#readMessageDialog").dialog({
			autoOpen: false,
			modal: true,
			height: 480,
			width: 640,
			buttons: {
						Ok: function()
						{
								$ (this).dialog("close");
						}
					}
		});
		
		$("#uploadimage0").change(function(){addFileInput();});
</script>
<div id="contactbookupperbar">
</div>

<div id="contactbook">
			
	<div id="newpost">
		<h3><a href="#">Nyt indlæg</a></h3>
		<div>
			<form name="newMessageForm" action="module/contactbook/add/<?=$childId?>" index.php?page=cbAddMessage" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="child" value=<?php echo '"' . $childId . '"'; ?> id="newMessageChildId" />
			<table>
				<tr>
					<td>Oprettet af: </td><!-- Automantisk indsættes brugernavn-->
					<td><input type="text" name="user" value=<?php echo '"' . $username . '"'; ?> readonly="readonly"/></td>
				</tr>
				<tr>
					<td>Overskrift: </td><!-- Overskrift, som skal være synlig fra oversigten-->
					<td><input name="subject" type="text" /></td>
				</tr>
				<tr>
					<td colspan="2"><textarea class="newpostinput" name="body"></textarea></td>
				</tr>
			<tr><td><input class="imageUpload" type="file" name="uploadImage0" id="uploadimage0" /></td></tr>
			<tr><td><input type="submit" name="submit" value="Send" /></td></tr>
			</table>
			</form>
		</div>
	</div>
	<!-- Here follows the complete accordian of contactbook messages. -->
	<div id="accordion">
		<?php
		if(count($messages) > 0)
		{
			foreach ($messages as $msg)
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
