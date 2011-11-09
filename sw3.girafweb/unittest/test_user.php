<?php
require_once(__DIR__ . "/config.php");

require_once(PATH_CODE . "include/user.class.inc");

require_once(__DIR__ . "/simpletest/autorun.php");

class TestGirafUser extends UnitTestCase
{
    private $user;
    
    /**
     * Sets up a useable GirafUser instance for later tests.
     */
    function setUp()
    {
        $this->user = new GirafUser();
    }

    /**
     * Tests the getting function that should work all the way down to
     * GirafRecord::getInstance().
     */
	function testGetFunction()
    {
        $u = GirafUser::getGirafUser(1);
        $this->assertTrue($u != false && $u != null);
	}
	
	/**
	 * Tests changing and reading the online status of a user.
	 */
	function testOnlineStatus()
	{
        $this->user = GirafUser::getGirafUser(1);
        $this->assertTrue($this->user->setOnlineStatus(GirafUser::STATUS_AWAY));
        $this->assertEqual(GirafUser::STATUS_AWAY, $this->user->getOnlineStatus());
	}
	
	/**
	 * Removes references again as they will not be needed.
	 */
	function tearDown()
	{
	    $this->user = null;
	}
	
	/**
	 * Tests the getting of groups associated with a user.
	 */
	function testGetGroups()
	{
	    $this->user = GirafUser::getGirafUser(1);
	    $groups = $this->user->getUserGroups();
		$this->assertNotNull($groups); // Must not be null.
        $this->assertIsA($groups, "Array"); // Must be an array.
	}
}

?>
