<script>
var lastModuleUrl = "";

function getChildIdFromAppButton(fullId)
{
	return fullId.substring(0, fullId.indexOf("-"));
}

function getAppIdFromAppButton(fullId)
{
	return fullId.substring(fullId.lastIndexOf("-") + 1);
}

/**
 * This function is used as a callback to AJAX calls when loading
 * sub modules (subcontrollers) for applications tied to single
 * children.
 * (guess what we use ignore and ignoreToo for).
 */
function printModule(contents)
{
	// Fill window.
	$("#window").html(contents);
}

function refreshModule()
{
	$.get(lastModuleUrl, {}, printModule);
}

/**
 * Reprints the list of groups this user has access to.
 */
function printGroups(data)
{
	// Empty the previous list.
	var n = "#groupSelector";

	hideLoader(n);
	
	$(n).empty();
	
	$(n).append("<option value=-1>Dine børn</option>");
	for (i=0;i<data.length;i++)
	{
		innerData = data[i];
		gId = innerData[0];
		gName = innerData[1];
		$(n).append('<option value="' + gId + '">' + gName + '</option>');
	}
}

/**
 * Reprints the selection box with devices.
 */
function printDevices(data)
{
	// console.debug("Printing devices.");
	
	// Empty the previous list.
	var n = "#deviceSelector";

	hideLoader("#applist");
	
	$(n).empty();
	
	$(n).append("<option value=-1>Alle enheder</option>");
	for (i=0;i<data.length;i++)
	{
		// console.debug(innerData);
		innerData = data[i];
		gId = innerData[0];
		gName = innerData[1];
		$(n).append('<option value="' + gId + '">' + gName + '</option>');
	}
	
	// console.debug("Done printing devices.");
}

/**
 * Reprints the list of children based on 
 */
function printChildren(data)
{
	// console.debug("Printing kids");
	var n = "#inner-child-list";
	
	hideLoader(n);
	
	$(n).empty();
	
	for(i=0;i<data.length;i++)
	{
		// console.debug(data[i]);

		var base = '<div id="giraf-child-base"><div class="menu-item child-item" id="child-__CHILD__ID__"><div class="image-div" id="image-div-__CHILD__ID__"><img class="menu-image" id="image-__CHILD__ID__" src="<?=BaseUrl() . '/img/profile-photo.jpg'?>" /></div><div class="menu-item-title" id="name-__CHILD__ID__">__FIRST__NAME__</div></div></div>';

		base = base.replace(/__CHILD__ID__/g, data[i][0]).replace(/__FIRST__NAME__/g, data[i][1]);
		
		// console.debug(base);

		$(n).append(base);
	}
	
	$(".child-item").click(function(){childClicked(event)});
	
	// console.debug("Done printing kids.");
}
var curChild = null;

/**
 * Reprints the list of applications for a specific device.
 */
function printApps(data)
{
	// console.debug("Printing Apps");
	var n = "#inner-app-list";
	
	hideLoader(n);
	
	$(n).empty();
	
	for(i=0;i<data.length;i++)
	{
		// console.debug(data[i]);

		var base = '<div class="menu-item app-item app-__APP__ID__ app-for-child-'+curChild+'" id="'+curChild+'-__APP__ID__"><span id="'+curChild+'__APP__ID__">__APP__NAME__</span></div>';

		base = base.replace(/__APP__ID__/g, data[i][0]).replace(/__APP__NAME__/g, data[i][1]);
		
		// console.debug(base);

		$(n).append(base);
	}
	
	$(".app-item").click(function(){appClicked(event)});
	
	// console.debug("Done printing kids.");
}

/**
 * Shows the AJAX loading icon within a typed element. All child
 * elements will be hidden until hideLoader() is called.
 */
function showLoader(target)
{
	$(target).children().hide();
	$(target).append('<img id="girafLoader" src="<?=BaseUrl()?>img/ajax-loader.gif"/>');
}

/**
 * Hides the AJAX loading icon again. Unscrupulously, all child elements
 * will be shown again.
 */
function hideLoader(target)
{
	$(target).children().show();
	$(target + " #girafLoader").remove();
}

function childClicked(eventdata)
{
	var cId = event.target.id;
	var childId = cId.substring(cId.lastIndexOf("-")+1);
	var name = ".app-for-child-" + childId;
	
	// Perform device loading and load the first device on the list.
	showLoader("#applist");
	$.getJSON("<?=BaseUrl()?>device/list/" + childId + "/<?=GirafRecord::RETURN_RECORD?>", {}, printDevices);	
	
	curChild = childId;
}

function appClicked(eventdata)
{
	var cId = event.target.id;
	var appId = getAppIdFromAppButton(cId);
	var kidId = getChildIdFromAppButton(cId);
	
	lastModuleUrl = "<?=BaseUrl()?>module/"+appId+"/"+kidId;
	
	refreshModule();	
}

$(document).ready(function(){
	$(".app-item").hide();
	
	$("#giraf-child-base").hide();
	
	$.getJSON("<?=BaseUrl()?>group/list/<?=$userId?>/<?=GirafRecord::RETURN_RECORD?>", {}, printGroups);
	
	$("#groupSelector").change(function(event){
		var group = event.target.value;
		showLoader("#inner-child-list");
		$.getJSON("<?=BaseUrl()?>child/list/group/" + group + "/<?=GirafRecord::RETURN_RECORD?>", {}, printChildren);
	});
	
	$("#deviceSelector").change(function(event){
		var device = event.target.value;
		showLoader("#inner-app-list");
		$.getJSON("<?=BaseUrl()?>app/list/device/" + device + "/<?=GirafRecord::RETURN_RECORD?>", {}, printApps);
	});
});</script>
<div id="site-box">
<div id="header">
	<h1 id="GIRAFTitle">GIRAF v1.0a</h1>
	<div id="accountbox">
		<li>My Account</li>
		<li><a href="index.php?page=login&action=logout">Log out</a></li>
	</div>
</div>
<div id="menu">
	<div id="childlist">
		<div id="groupSelectorDiv">
			<select id="groupSelector">
				
			</select>
		</div>
		<div id="inner-child-list"></div>
	</div>
	<div id="applist">
		<div id="deviceSelectorDiv">
			<select id="deviceSelector">
				<option value="0">Vælg enhed</option>
			</select>
		</div>
		<div id="inner-app-list"></div>
	</div>
</div>

<div id="window">
	<!-- ?="No module defined!"? -->
</div>
</div>
