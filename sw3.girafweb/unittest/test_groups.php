<?php
require_once(__DIR__ . "/config.php");

require_once(PATH_CODE . "include/group.class.inc");

require_once(__DIR__ . "/simpletest/autorun.php");

class TestGirafGroups extends UnitTestCase
{
    /**
     * Tests group retrieval
     */
	function testGetGroup()
    {
        $group = GirafGroup::getGirafGroup(1);
	}
	
	/**
	 * Tests the GirafGroup:getGirafGroups() function to retrieve arrays of
	 * groups. Both versions are tested.
	 */
	function testGetGroupList()
	{
	    $result = GirafGroup::getGirafGroups(null, GirafGroup::RETURN_PRIMARYKEY);
	    
	    $this->assertIsA($result, "array");
	    $this->assertIsA($result[0], "int");
	    
	    $result = GirafGroup::getGirafGroups(null, GirafGroup::RETURN_RECORD);
	    
	    $this->assertIsA($result, "array");
	    $this->assertIsA($result[0], "GirafGroup");
	}
}

?>
