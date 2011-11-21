<?php

// Pre-pre-processing that should always be at the top of your theme pages.

// This is our way of doing it. We like this way of doing it. We is only
// one person!
require_once("theme.conf");
require_once(GIRAF_INCLUDE . "session.class.inc");

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
			// Whee, we have a function for logging in now.
			$result = $s->loginUser($_POST["username"], $_POST["password"], true);
			
			if($result == true)
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
		case "register":
		{
			$res = users::registerNewUser(	$_POST["username"],
											auth::hashString($_POST["password"]),
											$_POST["mail"]);
			if ($res == false || !isset($res))
			{
				$s->errorMsg = "Something went wrong during user creation!";
			}
			else
			{
				$result = $s->loginUser($_POST["username"],
										$_POST["password"],
										true);
				// If this fails, something is seriously f'ed.
				if ($result == true)
				{
					$isLoggedIn = true;
					// Go to main page now that you're logged in.
					header("Location: index.php?page=main");
				}
				else
				{
					die("Something seriously f'ed happened loggin in with newly-created user.");
				}
			}
			break;
		}
		default:
		{
			break; // Do nothing.
		}
	}
}

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
	$("#forgotPassBox").hide();
	
	$("#registerButton").button().click(function()
	{
		if (registerCheck() == true)
		{
			$("#registerForm").submit();
		}
	});
	
	$(".fade-in-on-init").fadeIn('slow');
	
	$(".topMenu").buttonset();
	
	$("#radioLogin").click(function()
	{
		fadeSwitch("#loginBox");
	});
	
	$("#radioNew").click(function()
	{
		fadeSwitch("#registerBox");
	});
	
	$("#radioForgot").click(function()
	{
		fadeSwitch("#forgotPassBox");
	});
	
	// Makes a fadeout/fadein switch between two elements.
	function fadeSwitch(to)
	{
		$(".shown").fadeOut('fast', function(){$(to).fadeIn('fast')});
		$(".shown").removeClass("shown");
		$(to).addClass("shown");
	}
	
});</script>
</head>
<body>
	<div class="topMenu">
		<form>
			<input type="radio" id="radioLogin" name="topMenu" checked="checked"/><label for="radioLogin">Log ind</label>
			<input type="radio" id="radioNew" name="topMenu" /><label for="radioNew">Ny bruger</label>
			<input type="radio" id="radioForgot" name="topMenu" /><label for="radioForgot">Glemt kodeord</label>
		</form>
	</div>

	<div id="loginBox" class="shown">
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
		<tr><td>Brugernavn:</td><td><input type="text" name="username" /></td></tr>
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
		<form id="registerForm" name="register" action="index.php?page=login" method="POST" >
		<table>  
		<tr><td>Brugernavn: </td><td><input type="text" name="username" />
		<tr><td>Email:</td> <td><input type="text" name="mail" /></td></tr>
		<tr><td>Ønsket kodeord:</td><td><input type="password" name="password" /></td></tr>
		<tr><td>Gentag kodeord:</td><td><input type="password" name="password_check" /></td></tr>
		<tr><td colspan="2" align="center"><a id="registerButton" href="#">Registrer</a></td></tr>
		</table>
		<input type="hidden" name="action" value="register"/>
		</form>
		</div>
	</div>
	
	<div id="forgotPassBox">
		<div class="ui-widget ui-widget-content ui-corner-all center-box">
			<div class="ui-widget-header center-box-header">Glemt password</div>
			<form id="forgotPassForm" name="forgot" action="index.php?page=login" method="POST">
				<table>
					<tr>
						<td>E-mail-adresse: </td><td><input type="text" name="mail" /></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</body>
</html>
