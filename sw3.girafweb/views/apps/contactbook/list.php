<div id="cb-message-list-div">
<script>
var cb_message_list_url = "<?=BaseUrl()."module/$appId/$childId"?>";
	
function reloadMessageList()
{
	$.get(cb_message_list_url, {}, function(data){$("#cb-message-list-div").html(data)});
}
	
// Adds a new file input thingy to the form.
function addFileInput()
{
	// Check to see if the counter has been initialized
	if ( typeof addFileInput.counter == 'undefined' )
	{
		// It has not, perform the initilization
		addFileInput.counter = 0;
	}
	
	preCount = addFileInput.counter;
	postCount = preCount + 1;
	
	html = '<br/><input class="imageUpload" type="file" name="uploadImage' + postCount + '" id="uploadimage';
	html_post = '">';
	
	theId = "#uploadimage" + preCount;
	
	targetContent = html + postCount + html_post;
	
	// alert(theId); 
	
	$(theId).after(targetContent);
	
	// Register new event handler.
	$("#uploadimage" + postCount).change(function(){addFileInput();});
	
	// Finally, iterate.
	addFileInput.counter += 1;
}

// Reloads the message window with data from the requested message id.
function showMessage(id)
{
	var d = "#readMessageDialog";
	var c = "#messageDialogContents";
	if ($(d).dialog("isOpen")) $(d).dialog("close");
	$.get("<?=BaseUrl()?>module/contactbook/show/"+id, {}, function(contents)
	{
		$(c).html(contents);
		$(d).dialog("open");
		$(d).dialog('option', 'title', $("#messageSubject").html());
	});
}

// Verifies that the data in a form element is sufficient.
// Highlights form elements that are not properly filled out. 
function verifyForm(id, minlen)
{
	if (!id) return false; // Bad in all cases.
	if (!minlen) minlen = 10;
	var len = $(id).val().length;
	if (len < minlen)
	{
		$(id).effect("highlight", { mode: "show" }, 6000);
		return false;
	}
	else
	{
		$(id).effect("highlight", { mode: "hide" }, 1);
		return true;
	}
}

function onNewMessageCreated(data)
{
	if (data == "success")
	{
		// alert ("Message posted succesfully.");
		reloadMessageList();
	}
	else
	{
		alert("Server reported error during post action.");
		console.debug(data);
	}
}	
	
$(document).ready(function(){
	$("#accordion").accordion({
		collapsible: true,
		//active: false
	});

	$("#newpost").accordion({
		collapsible: true,
		active: false
	});
	
	// Define behaviour for buttons that open single messages.
	$(".readmoreButton").button().click(function(){
		// Load data.
		var msgId = event.target.id;
		msgId = msgId.substring(msgId.indexOf("-")+1);
		
		// Soft remove the "new" part.
		$("#new-" + msgId).remove();
		
		// Pop out message.
		showMessage(msgId);
	});
	
	// Create the dialog box to contain further messages.
	$("#readMessageDialog").dialog({
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
	
	// Handler for the first image to upload.
	$("#uploadimage0").change(function(){addFileInput();});
	
	$("#newMessageSubmitButton").click(function(){
		if (verifyForm("#newMessageSubject", 3) == false) return;
		if (verifyForm("#newMessageBody", 10) == false) return;
		$("#newMessageForm").ajaxSubmit(onNewMessageCreated);
	});
	
	// Add the new stylesheet.
	$("head").append('<link rel="stylesheet" href="<?=BaseUrl()?>css/apps/contactbook/stylesheet.css" type="text/css" />');
});</script>
<div id="contactbookupperbar">
</div>

<div id="contactbook">
			
	<div id="newpost">
		<h3><a href="#">Nyt indlæg</a></h3>
		<div>
			<form id="newMessageForm" name="newMessageForm" action="module/contactbook/add" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="user" value="<?=$userId?>" />
			<input type="hidden" name="child" value="<?=$childId?>" />
			<table>
				<tr>
					<td>Oprettet af: </td>
					<td><input type="text" value="<?=$username?>" readonly="readonly"/></td>
				</tr>
				<tr>
					<td>Overskrift: </td>
					<td><input id="newMessageSubject" type="text" name="subject" /></td>
				</tr>
				<tr>
					<td colspan="2"><textarea rows="5" class="input-textbox" name="body" id="newMessageBody"></textarea></td>
				</tr>
			<tr><td><input class="imageUpload" type="file" id="uploadimage0" name="uploadimage0" /></td></tr>
			<tr><td><input type="button" id="newMessageSubmitButton" value="Send" /></td></tr>
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
				<h3><a href="#"><?php echo $msg->msgTimestamp; ?> <?php echo $msg->msgSubject; ?><?php
																									if (!$msg->isRead($userId)) echo '<span class="new" id="new-' . $msg->id . '">Ulæst</span>';
																								?></a></h3>
				<div>
					<?php echo $msg->msgBody; ?>
					<input id=<?='"message-' . $msg->id . '"'?> class="readmoreButton" type="button" value="Læs mere"/>
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
</div>
