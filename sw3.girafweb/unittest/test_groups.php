<?php
require_once(__DIR__ . "/config.php");

require_once(PATH_CODE . "include/groups.func.inc");

require_once(__DIR__ . "/simpletest/autorun.php");

class TestGirafGroups extends UnitTestCase
{
    /**
     * Tests the three variants (and outcomes) of groups::getGroupData().
     */
	function testGetGroupData()
    {
        $groupType = groups::getGroupData(1, groups::RETURN_GROUP);
        $this->assertIsA($groupType, "GirafGroup");
        
        $resultType = groups::getGroupData(1, groups::RETURN_RESULTSET);
        $this->assertIsA($resultType, "MySQLi_Result");
        
        $rowType = groups::getGroupData(1, groups::RETURN_ROW);
        $this->assertIsA($rowType, "array");
	}
	
	/**
	 * Tests the groups::getGroups() function. Tests array return type and
	 * numeric data type within the array.
	 */
	function testGetGroupList()
	{
	    $result = groups::getGroups();
	    $this->assertIsA($result, "Array");
	    foreach ($result as $single)
	    {
	        $this->assertTrue(is_numeric($single));
	    }
	}
}

?>
