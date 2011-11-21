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

// Debug.


?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>GIRAF Admin</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $p; ?>/css/css.css" />
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
	<script>$(document).ready(function(){
		$("#childGroupSelect").change(function(){
			// alert("You clicked" + $("#childGroupSelect").val());
			$(".child-select").hide();
			$(".group-" + $("#childGroupSelect").val()).show();
		});
		
		$(".app-select").hide();
		
		// $(".child-picker").button().click(function(){
		$("#childlist").buttonset();
		$(".child-picker").click(function(){
			// alert("I should show you apps for this child now!");
			var callerId = event.target.id;
			var childId = callerId.substring(callerId.length-1);
			var name = "#app-picker-" + childId;
			// alert(name);
			
			// Pretty, but glitchy.
			// $(".app-select").slideUp('fast', function(){$(".for-child-" + childId).slideDown('fast');});
			
			// Functional, but poppy.
			// alert($(name).html());
			$(".app-select").hide();
			$(name).show();;
		});
		
		$(".app-picker").button().click(function(){
			// alert("Now we should display the app module.");
			// Simple hard-coding for now.
			// window
			$.get("themes/default/cbMessages.tpl.php", { child : 1}, printModule);
		});
		
		function printModule(contents, ignore, ignoreToo)
		{
			// Fill window.
			$("#window").html(contents);
			
			// Re-initialize some jquery stuff.
			$("#accordion").accordion({
				collapsible: true,
				//active: false
			});

			$("#newpost").accordion({
				collapsible: true,
				active: false
			});
			
			$(".readmoreButton").button().click(function(){
				alert("Getting more for more.");
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
				<form>
				<?php
					// Find all children that this user has access to. That
					// encompasses finding all groups that this user is a
					// member of and then children for each group. I'd
					// rather we have a dropdown list of groups the user has
					// access to, bit of a filter, y'know.
					/*
					$groups = $currentUserData->getUserGroups();
					
					echo "<form>";
					echo "<select id=\"childGroupSelect\"";
					
					foreach ($groups as $group)
					{
						
						$data = GirafGroup::getGirafGroup($group);
						// var_dump($group, $data);
						echo "<option value=\"$group\">" . $data->groupName . "</option>";
					}
					*/
					// Get *all* apps for a single child.
					
					// As I said, we're bruteforcing because of time
					// Constraints.
					
					foreach($kids as $child)
					{
						$id = "\"childPicker-" . $child->id . "\"";
				?>
					<div class="child-select group-0">
						<input type="radio" name="children" class="menu-image child-image ui-widget-content child-picker" id=<?php echo $id; ?> />
							<label for=<?php echo $id; ?>>
								<?php echo $child->getFirstName(); ?>
							</label>
					</div>
				<?php
					}
				?>
				</form>
				<!-- Skal indeholde en liste af børn, som er tilknyttet den aktuelle bruger.
				For forældrene skal der kun være adgang til personens eget/egne barn/børn, hvor pædagoger skal have 
				adgang til alle børn i den pågældende børnehave
				-->
			</div>
			<div id="applist">
				<?php
				
				// PHP: print out all apps with class/id that determines child.
				// js: hide/show depending on picked child.
				
				foreach($kidsApps as $kidId => $apps)
				{
					foreach ($apps as $app)
					{
						// var_dump($app);
						// echo "id=\"app-picker-$kidId\"";
					?>
					<div id="app-picker-<?php echo $kidId; ?>" class="app-select menu-image">
						<a href="#" id="app-<?php echo $kidId . "-" . $app->id; ?>"/><?php echo $app->applicationName; ?></a>
					</div>
					<?php
					}
				}
				
				?>
				<!-- Skal indeholde:
						En liste af applikationer installeret på tabletten
						Et Settingsapplikation, som indeholder personlige oplysninger såsom handicap
						En markedsapplikation, som giver mulighed for at hente nye applikationer til tabletten
				-->
			</div>
		</div>
		
		<div id="window">
			<!-- Hovedvindue, som skal vise indhold og indstillinger for samtlige applikationer, samt Settings og GIRAFMarket
			-->
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

