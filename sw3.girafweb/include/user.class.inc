<?php

require_once(__DIR__ . "/record.class.inc");

/**
 * Data container class for a user of the GIRAF system. This is typically
 * parents, kindergarten eachers and admins.
 */
class GirafUser extends GirafRecord
{
    function __construct()
    {
        parent::__construct(TABLE_USERS, "GirafUser");
    }
    
    /**
     * Attempts to retrieve an existing user from the database and returns them
     * as a GirafUser instance.
     * /param uId the userId in the database corresponding to the user you want.
     * /return A new GirafUser object with the data from the database.
     */
    public static function getGirafUser($uId)
    {
        return parent::getInstance($uId);
    }
}

?>
