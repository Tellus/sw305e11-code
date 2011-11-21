<?php

// Pre-pre-processing that should always be at the top of your theme pages.

// This is our way of doing it. We like this way of doing it. We is only
// one person!
require_once("theme.conf");
require_once(GIRAF_INCLUDE . "session.class.inc");

?>
<!DOCTYPE html>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo THEME_PATH; ?>/css/css.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo THEME_PATH; ?>/css/girafbase.css" />
	<link type="text/css" href="<?php echo THEME_PATH; ?>/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="<?php echo THEME_PATH; ?>/js/jquery-1.7.js"></script>
	<script type="text/javascript" src="<?php echo THEME_PATH; ?>/js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript">$(document).ready(function(){ 
	$("#loginButton").button().click(function()
	{
		$("#loginForm").submit();
	});
	
	// Checks entered login details for consistency.
	function registerCheck()
	{
		return true;
	}
	
	$("#openRegisterFormButton").button().click(function()
	{
		$("#loginBox").fadeOut('fast', function(){$("#registerBox").fadeIn('fast')});
		
	});
	
	$("#registerBox").hide();
	
	$("#registerButton").button().click(function()
	{
		if (registerCheck() == true)
		{
			$("#registerForm").submit();
		}
	});
	
	$("#closeRegisterFormButton").button({text: false, icons: {secondary:"ui-icon-circle-close"}}).click(function()
	{
		$("#registerBox").fadeOut('fast', function(){$("#loginBox").fadeIn('fast')});
	});
	
	$(".fade-in-on-init").fadeIn('slow');
	
});</script>
</head>
<?php

// Init done. At this point we should branch a bit depending on action.
if(isset($_POST["action"]))
{
	// Get session. Huarh!
	$s = GirafSession::getSession();
	switch($_POST["action"])
	{
		case "login":
		{
			// Step 1. Get userId from passed name.
			$id = GirafUser::getUser($_POST["username"]);
			if ($id == null)
			{
				$s->errorMsg = "The user '" . $_POST["username"] . "' is unknown.";
				break;
			}
			elseif (!auth::matchPassword($id, $_POST["password"], true))
			{
				$s->errorMsg = "The password was incorrect.";
				break;
			}
			else
			{
				$isLoggedIn = true;
				header("Location: index.php?page=home");
			}
			break;
		}
		case "logout":
		{
			$s->close();
			$isLoggedIn = false;
			break;
		}
		default:
		{
			break; // Do nothing.
		}
	}
}

?>
<body>
	<div class="topBar"><a id="openRegisterFormButton" href="#">Registrer en ny bruger</a></div>

	<div id="loginBox">
		<div class="ui-widget ui-widget-content ui-corner-all center-box">
		<div class="ui-widget-header center-box-header">Log ind</div>
		<form id="loginForm" name="login" action="index.php?page=login" method="POST" >
		<table>
<?php
	// Test
	if ($s->errorMsg != null)
	{
?>
		<tr><td class="fade-in-on-init giraf-user-error"><?php echo $s->errorMsg ?></td></tr>
<?php
	}
?>
		<tr><td>Brugernavn:</td> <td><input type="text" name="username" /></td></tr>
		<tr><td>Password:</td><td><input type="password" name="password" /></td></tr>
		<tr><td colspan="2" align="right"><a id="loginButton" href="#">Login</a></td></tr>
		</table>
		<input type="hidden" name="action" value="login"/>
		</form>
		</div>
	</div>
	
	<div id="registerBox">
		<div class="ui-widget ui-widget-content ui-corner-all center-box">
		<div class="ui-widget-header center-box-header">registrer ny bruger</div>
		<form id="registerForm" name="register" action="login.php" method="POST" >
		<table>  
		<tr><td>Brugernavn: </td><td><input type="text" name="username" />
		<tr><td>Email:</td> <td><input type="text" name="mail" /></td></tr>
		<tr><td>Ønsket kodeord:</td><td><input type="password" name="password" /></td></tr>
		<tr><td>Gentag kodeord:</td><td><input type="password" name="password_check" /></td></tr>
		<tr><td colspan="1" align="center"><a id="registerButton" href="#">Login</a></td><td><a align="right" id="closeRegisterFormButton">Close</a></td></tr>
		</table>
		<input type="hidden" name="action" value="register"/>
		</form>
		</div>
	</div>
</body>
</html>
