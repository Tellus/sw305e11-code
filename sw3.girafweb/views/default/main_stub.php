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
 * Reprints the list of children based on 
 */
function printChildren(data)
{
	var n = "#inner-child-list";
	
	hideLoader(n);
	
	$(n).empty();
	
	for(i=0;i<data.length;i++)
	{
		console.debug(data[i]);

		var base = '<div id="giraf-child-base"><div class="menu-item child-item" id="child-__CHILD__ID__"><div class="image-div" id="image-div-__CHILD__ID__"><img class="menu-image" id="image-__CHILD__ID__" src="<?=BaseUrl() . '/img/profile-photo.jpg'?>" /></div><div class="menu-item-title" id="name-__CHILD__ID__">__FIRST__NAME__</div></div></div>';

		base = base.replace(/__CHILD__ID__/g, data[i][0]).replace(/__FIRST__NAME__/g, data[i][1]);
		
		console.debug(base);

		$(n).append(base);
	}
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

function hideLoader(target)
{
	$(target).children().show();
	$(target + " #girafLoader").remove();
}

$(document).ready(function(){
	$(".app-item").hide();
	
	$("#giraf-child-base").hide();
	
	$(".child-item").click(function()
	{
		var cId = event.target.id;
		var childId = cId.substring(cId.lastIndexOf("-")+1);
		var name = ".app-for-child-" + childId;
		// console.debug(name);
		
		// Pretty, but glitchy.
		// $(".app-select").slideUp('fast', function(){$(".for-child-" + childId).slideDown('fast');});
		
		// Functional, but poppy.
		$(".app-item").hide();
		$(name).show();;
	});
	
	$(".app-item").click(function()
	{
		var cId = event.target.id;
		// console.debug(cId);
		var appId = getAppIdFromAppButton(cId);
		var kidId = getChildIdFromAppButton(cId);
		
		// alert(appId + " and " + kidId);
		// $.get("themes/default/cbMessages.tpl.php", { child : kidId}, printModule);
		// var gUrl = "<?=BaseUrl()?>module/"+appId+"/"+kidId;
		lastModuleUrl = "<?=BaseUrl()?>module/"+appId+"/"+kidId;
		// console.debug(gUrl);
		refreshModule();
	});
	
	$.getJSON("<?=BaseUrl()?>group/list/<?=$userId?>/<?=GirafRecord::RETURN_RECORD?>", {}, printGroups);
	
	$("#groupSelector").change(function(event){
		var group = event.target.value;
		showLoader("#inner-child-list");
		$.getJSON("<?=BaseUrl()?>child/list/group/" + group + "/<?=GirafRecord::RETURN_RECORD?>", {}, printChildren);
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
		<div id="inner-child-list">
		</div>
	</div>
	<div id="applist">
		<div id="deviceSelectorDiv">
			<select id="deviceSelector">
				<option value="0">Vælg enhed</option>
			</select>
		</div>
		<div id="inner-app-list">
		<!-- No content -->
		</div>
	</div>
</div>

<div id="window">
	<!-- ?="No module defined!"? -->
</div>
</div>
