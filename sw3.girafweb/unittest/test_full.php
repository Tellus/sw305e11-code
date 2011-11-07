<?php
require_once(__DIR__ . "/config.php");

// Require base file for simpletest.
require_once(__DIR__ . "/simpletest/autorun.php");

/**
 * Base suite that performs all tests we can find. Requires no modifications as
 * it will just pull all files with the pattern "test_<name>.php" except itself.
 */
class AllTests extends TestSuite
{
    function __construct()
    {
        parent::__construct();

        $this->TestSuite("All tests");

        $files = scandir(__DIR__);
        foreach ($files as $file)
        {
            if (preg_match("/test_.+\.php$/", $file) == 1 && basename(__FILE__) != $file)
            {
                $this->addFile(__DIR__ . "/" . $file);
                if(SimpleReporter::inCli()) echo "File " . PATH_UNITTEST . "/$file added.\n";
            }
            else
            {
                if(SimpleReporter::inCli()) echo "File $file did not match automatic inclusion.\n";
            }
        }
    }
}

?>
