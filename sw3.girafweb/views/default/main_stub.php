<?php // Injecting js. ?>
<script>
$(document).ready(function(){
	$(".app-select").hide();
	
	$(".child-picker").button().click(function(){
		var callerId = event.target.id;
		var childId = callerId.substring(callerId.length-1);
		var name = ".app-for-child-" + childId;
		
		// Pretty, but glitchy.
		// $(".app-select").slideUp('fast', function(){$(".for-child-" + childId).slideDown('fast');});
		
		// Functional, but poppy.
		$(".app-select").hide();
		$(name).show();;
	});
	
	$(".app-picker").button().click(function(){
		var callerId = event.target.id;
		var appId = getAppIdFromAppButton(callerId);
		var kidId = getChildIdFromAppButton(callerId);
		
		// alert(appId + " and " + kidId);
		$.get("themes/default/cbMessages.tpl.php", { child : kidId}, printModule);
	});
	
	function getChildIdFromAppButton(fullId)
	{
		return fullId.substring(fullId.indexOf("-")+1, fullId.lastIndexOf("-"));
	}
	
	function getAppIdFromAppButton(fullId)
	{
		return fullId.substring(fullId.lastIndexOf("-") + 1);
	}
	
	function printModule(contents, ignore, ignoreToo)
	{
		// Fill window.
		$("#window").html(contents);
		
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
			$.get("themes/default/cbMessage.tpl.php", { message : msgId }, function(contents, ign, ignToo)
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
		
	}
	
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
		
		html = '<input class="imageUpload" type="file" name="uploadImage' + postCount + '" id="uploadimage';
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
});
	</script>
<div id="header"><h1 id="GIRAFTitle">Logo + Title</h1>

	<div id="accountbox">
		<img src="<?php echo $p; ?>/face-icon.png" id="accpic" />
			<!--Evt et profilbillede, ikke besluttet om skal implementeres-->
		<br/>
		<li />My Account
			<!-- Skal åbne popup med account settings-->
		<li><a href="index.php?page=login&action=logout">Log out</a></li>
			<!-- Skal logge brugeren af -->
	</div>
</div>
<div id="menu">
	<div id="childlist">
		<?php
			foreach($kids as $child)
			{
				$id = "\"childPicker-" . $child->id . "\"";
		?>
			<div class="child-select">
				<a class="child-picker menu-image" href="#" id=<?php echo $id; ?> ><?php echo $child->getFirstName(); ?></a>
			</div>
		<?php
			}
		?>
	</div>
	<div id="applist">
		<?php
		
		// PHP: print out all apps with class/id that determines child.
		// js: hide/show depending on picked child.
		
		foreach($kidsApps as $kidId => $apps)
		{
			foreach ($apps as $app)
			{
			?>
			<div class="app-select app-for-child-<?php echo $kidId; ?>">
				<a class="app-picker menu-image" id="app-<?php echo $kidId . "-" . $app->id; ?>"/><?php echo $app->applicationName; ?></a>
			</div>
			<?php
			}
		}
		
		?>
	</div>
</div>

<div id="window">
	<?="No module defined!"?>
</div>
