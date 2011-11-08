<?php

require_once("auth.func.inc");

echo ("unittestpassword becomes " . auth::hashString("unittestpassword"));

?>
