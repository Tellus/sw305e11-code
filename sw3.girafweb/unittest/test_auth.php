<?php

require_once(PATH_CODE . "include/auth.func.inc");

class TestAuthFunctions extends UnitTestCase
{
    /**
     * A hashed version of our reference text.
     */
    private $referenceHash;
    
    /**
     * Our reference text in clear form.
     */
    private $referenceClear;

    function setUp()
    {
        $this->referenceClear = "unittestpassword";
    
        // The dynamic method is pure masturbation.
        // $this->referenceHash = auth::hashString("unittestpassword");    
        
        // The static gives us a solid reference point, albeit one that must be
        // changed if we change the crypt form.
        $this->referenceHash = 'ergf0h34280hufdg$MmNiY8YYkVsNI2VB.sYbO/ef098QurrIcOMGS2peARzmPGItVwIbaIodGk7qb9EJpABMo6bYFa/GUjnYo1hVA';
    }

    /**
     * The hash test relies on itself and proves nothing more than working code,
     * not functionality.
     */
    function testHash()
    {
        $this->assertEqual($this->referenceHash, auth::hashString($this->referenceClear),  "Testing hash consistency.");
    }
    
    /**
     * Tests hashed password retrieval.
     */
    function testPasswordGet()
    {
        $this->assertEqual($this->referenceHash, auth::getPassword(1), "Testing password retrieval.");
    }
    
    /**
     * Tests password matching.
     */
    function testPasswordMatch()
    {
        $this->assertTrue(auth::matchPassword(1, "unittestpassword", true), "Testing clear-text password matching.");
        $this->assertTrue(auth::matchPassword(1, $this->referenceHash, false), "Testing hashed password matching.");
        $this->assertFalse(auth::matchPassword(1, "wrongpassword", true), "Testing wrong clear-text password matching.");
        $this->assertFalse(auth::matchPassword(1, auth::hashString("wrongpassword"), false), "Testing wrong hashed password matching.");
    }
}

?>
