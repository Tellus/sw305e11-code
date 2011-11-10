<?php

require_once(__DIR__ . "/include/html.func.inc");
require_once(__DIR__ . "/include/auth.func.inc");

/**
 * Login page. Simple. Only action is to accept a user/pass combination and
 * and react to user input.
 */

$loginOk = null;

if (isset($_POST["username"]) && isset($_POST["userPass"]))
{
    if (auth::matchPassword($_POST["username"], $_POST["userPass"], true))
    {
        $loginOk = true;
        //header("Location: index.php");
    }
    else
    {
        $loginOk = false;
    }
}

startHead();
endHead();
startBody();

// echo auth::hashString("password");

?>

<div class="dialogArea">
<div class="formText">Please log in with your credentials.</div>
<?php

if ($loginOk === false)
{
?>
<div class="errorText">The user/pass combination did not match.</div>
<?php
}
else
{
?>
<div class="successText">Bingo!</div>
<?php
}

?>
<div class="formContent">
<form method="post">
<table>
<tr><td>Username:</td><td><input type="text" name="username"/></td></tr>
<tr><td>Password:</td><td><input type="password" name="userPass"/></td></tr>
<tr><td><input type="submit" /></td></tr>
</table>
</form>
</div>
</div>

<?php

endBody();

?>
