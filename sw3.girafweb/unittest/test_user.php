<?php
require_once(__DIR__ . "/config.php");

require_once(PATH_CODE . "include/user.class.inc");

require_once(__DIR__ . "/simpletest/autorun.php");

class TestGirafUser extends UnitTestCase
{
	function testGetFunction()
    {
        $uTrue = new GirafUser();
        $u = GirafUser::getGirafUser(1);
        $this->assertTrue($u != false && $u != null);
	}
}

?>
