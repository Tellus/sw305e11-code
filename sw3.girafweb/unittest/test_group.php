<?php
require_once(__DIR__ . "/config.php");

require_once(PATH_CODE . "include/group.class.inc");

require_once(__DIR__ . "/simpletest/autorun.php");

class TestGirafGroup extends UnitTestCase
{
	function testGetFunction()
    {
        $uTrue = new GirafGroup();
        $u = GirafGroup::getGirafGroup(1);
        $this->assertTrue($u != false && $u != null);
	}
}

?>
