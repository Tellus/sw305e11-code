<?php
require_once(PATH_CODE . "include/newspost.class.inc");
require_once(PATH_CODE . "include/session.class.inc");

require_once(__DIR__ . "/simpletest/autorun.php");

class TestGirafNewsPost extends UnitTestCase
{
    function setUp()
    {
        // News relies on having an active session with a user. We need to set
        // up a fake user session we can work with.
        $session = GirafSession::getSession();
        GirafSession::set("userId", 1);
    }

    function testBaseGroupNews()
    {
        // Testing default group with default parameters.
        $uNews = GirafNewsPost::getGroupNews();
        $this->assertNotNull($uNews);
        $this->assertIsA($uNews, "array");
        if (count($uNews) > 0) $this->assertIsA($uNews[0], "int");
        
        // Testing default group with specific (although default) parameters.
        $uNews = GirafNewsPost::getGroupNews(null, GirafNewsPost::RETURN_PRIMARYKEY);
        $this->assertNotNull($uNews);
        $this->assertIsA($uNews, "array");
        if (count($uNews) > 0) $this->assertIsA($uNews[0], "int");
        
        // Testing default group with specific parameters.
        $uNews = GirafNewsPost::getGroupNews(null, GirafNewsPost::RETURN_RECORD);
        $this->assertIsA($uNews, "array");
        if (count($uNews) > 0) $this->assertIsA($uNews[0], "GirafNewsPost");
    }

	function testSingleGroupNews()
    {
        // Testing default group with default parameters.
        $uNews = GirafNewsPost::getGroupNews(1);
        $this->assertNotNull($uNews);
        $this->assertIsA($uNews, "array");
        if (count($uNews) > 0) $this->assertIsA($uNews[0], "int");
        
        // Testing default group with specific (although default) parameters.
        $uNews = GirafNewsPost::getGroupNews(1, GirafNewsPost::RETURN_PRIMARYKEY);
        $this->assertNotNull($uNews);
        $this->assertIsA($uNews, "array");
        if (count($uNews) > 0) $this->assertIsA($uNews[0], "int");
        
        // Testing default group with specific parameters.
        $uNews = GirafNewsPost::getGroupNews(1, GirafNewsPost::RETURN_RECORD);
        $this->assertIsA($uNews, "array");
        if (count($uNews) > 0) $this->assertIsA($uNews[0], "GirafNewsPost");
	}
	
	function testUserNews()
	{
        // Get news for the currently signed in user.
        $uNews = GirafNewsPost::getUserNews();
        $this->assertNotNull($uNews);
        $this->assertIsA($uNews, "array");
        if (count($uNews) > 0) $this->assertIsA($uNews[0], "int");
        
        // Testing default group with specific (although default) parameters.
        $uNews = GirafNewsPost::getUserNews(1, GirafNewsPost::RETURN_PRIMARYKEY);
        $this->assertNotNull($uNews);
        $this->assertIsA($uNews, "array");
        if (count($uNews) > 0) $this->assertIsA($uNews[0], "int");
        
        // Testing default group with specific parameters.
        $uNews = GirafNewsPost::getUserNews(1, GirafNewsPost::RETURN_RECORD);
        $this->assertIsA($uNews, "array");
        if (count($uNews) > 0) $this->assertIsA($uNews[0], "GirafNewsPost");
	}
	
	function tearDown()
	{
	    GirafSession::close();
	}
}

?>
