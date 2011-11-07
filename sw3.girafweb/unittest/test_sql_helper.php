<?php

require_once(__DIR__ . "/config.php");

require_once(PATH_CODE . "include/sql_helper.inc");

require_once(__DIR__ . "/simpletest/autorun.php");

class TestSqlHelper extends UnitTestCase
{
	function testInitialization()
    {
        $this->assertNotNull(sql_helper::getConnection());
	}
	
	function testGoodQuery()
	{
	    $this->assertNotNull(sql_helper::selectQuery("SELECT * FROM errors"));
	}
	
	function testBadQuery()
	{
	    $this->assertFalse(sql_helper::selectQuery("SELECT MY ASS FROM YOUR MOMMA )!¤(/"));
	}
}

?>
