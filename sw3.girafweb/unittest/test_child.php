<?php
require_once(__DIR__ . "/config.php");

require_once(PATH_CODE . "include/child.class.inc");

require_once(__DIR__ . "/simpletest/autorun.php");

class TestGirafChild extends UnitTestCase
{
	function testGetFunction()
    {
        $uTrue = new GirafChild();
        $u = GirafChild::getGirafChild(1);
        $this->assertNotNull($u, "Ensuring non-null return on child creation.");
        $this->assertTrue($u != false, "Ensuring non-false return on child creation.");
	}
}

?>
