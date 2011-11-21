<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

// Initializing code.
// $page_path = dirname($_SERVER["PHP_SELF"]);
require_once("theme.conf");

$p = dirname($_SERVER["SCRIPT_NAME"]) . "/" . THEME_PATH;

// Since it's a feature used numerous times throughout this script,
// let's just get the current user.

$currentUser = users::getCurrentUser();
$currentUser = 1;

$currentUserData = GirafUser::getGirafUser($currentUser);

// Alright, time constraint.
// We're gonna bruteforce the fuck out of this thing.

// Get all children for this user's groups.
// Even more bruteforce. Get *all* children.
$kids = GirafChild::getGirafChildren(null, GirafChild::RETURN_RECORD);


$kidsApps = array(); // Array with children and their apps.
// Get all apps on all devices for all the children.
foreach($kids as $child)
{
	// Get devices.
	$devs = GirafDevice::getDevices("ownerId=" . $child->id, GirafRecord::RETURN_RECORD);
	$childApps = array(); // Key = appId.
	// Get apps for each device.
	
	// For each device, get the apps.
	foreach ($devs as $device)
	{
		$apps = GirafDevice::getAppsOnDevice($device->id);
		// For each app id, retrieve app data and give to the top childApps array.
		
		foreach ($apps as $appId)
		{
			$appData = GirafApplication::getApplication($appId);
			// Only add the app if it is not null and it hasn't already been added.
			if ($appData != null)
			{
				if (!array_key_exists($appId, $childApps)) $childApps[$appId] = $appData;
			}
		}
	}
	
	// At this point, we should have all unique apps for a cihld in
	// childApps.
	$kidsApps[$child->id] = $childApps;
}

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>GIRAF Admin</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $p; ?>/css/css.css" />
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
	<script>$(document).ready(function(){
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
			var childId = callerId.substring(callerId.lastIndexOf("-") + 1);
			// alert(childId);
			$.get("themes/default/cbMessages.tpl.php", { child : 1}, printModule);
		});
		
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
				$.get("themes/default/cbMessage.tpl.php", { message : 1 }, function(contents, ign, ignToo)
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
		}
	});</script>
	
</head>
<body>
	<div id="main">
	
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
			<?php
				// We should fill this place with the template of the chosen module.
			?>
		</div>
		
		<div id="lowerbar">
			Support: 98 12 34 56 / help@mail.com
		</div>	
	</div>
</body>
</html>

