<script type="text/javascript">
$(document).ready(function(){ 
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
});
</script>
<div class="topMenu">
	<form>
		<input type="radio" id="radioLogin" name="topMenu" checked="checked"/><label for="radioLogin">Log ind</label>
		<input type="radio" id="radioNew" name="topMenu" /><label for="radioNew">Ny bruger</label>
		<input type="radio" id="radioForgot" name="topMenu" /><label for="radioForgot">Glemt kodeord</label>
	</form>
</div>
<?php
	if (isset($error)) echo "<div>$error</div>";
?>
<div id="loginBox" class="shown">
	<div class="ui-widget ui-widget-content ui-corner-all center-box">
	<div class="ui-widget-header center-box-header">Log ind</div>
	<form id="loginForm" name="login" action="<?=BaseUrl("login/login")?>" method="POST" >
	<table>
	<tr><td>Brugernavn:</td><td><input type="text" name="username" /></td></tr>
	<tr><td>Password:</td><td><input type="password" name="password" /></td></tr>
	<tr><td colspan="2" align="right"><a id="loginButton" href="#">Login</a></td></tr>
	</table>
	</form>
	</div>
</div>

<div id="registerBox">
	<div class="ui-widget ui-widget-content ui-corner-all center-box">
	<div class="ui-widget-header center-box-header">registrer ny bruger</div>
	<form id="registerForm" name="register" action="<?=BaseUrl("login/register")?>" method="POST" >
	<table>  
	<tr><td>Brugernavn: </td><td><input type="text" name="username" />
	<tr><td>Email:</td> <td><input type="text" name="mail" /></td></tr>
	<tr><td>Ønsket kodeord:</td><td><input type="password" name="password" /></td></tr>
	<tr><td>Gentag kodeord:</td><td><input type="password" name="password_check" /></td></tr>
	<tr><td colspan="2" align="center"><a id="registerButton" href="#">Registrer</a></td></tr>
	</table>
	</form>
	</div>
</div>

<div id="forgotPassBox">
	<div class="ui-widget ui-widget-content ui-corner-all center-box">
		<div class="ui-widget-header center-box-header">Glemt password</div>
		<form id="forgotPassForm" name="forgot" action="<?=BaseUrl("login/forgot")?>" method="POST">
			<table>
				<tr>
					<td>E-mail-adresse: </td><td><input type="text" name="mail" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>
