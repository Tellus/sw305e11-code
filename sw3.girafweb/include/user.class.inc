<?php

require_once(__DIR__ . "/record.class.inc");

/**
 * Data container class for a user of the GIRAF system. This is typically
 * parents, kindergarten eachers and admins.
 */
class GirafUser extends GirafRecord
{
    // The user is currently online and visible.
    const STATUS_ONLINE = 0;
    
    // The user is offline.
    const STATUS_OFFLINE = 1;
    
    // The user is away.
    const STATUS_AWAY = 2;
    
    // The user is busy.
    const STATUS_BUSY = 3;
    
    // The user is online but invisible.
    const STATUS_HIDDEN = 4;

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
    
    /**
     * Sets the user's current online status.
     * \param $status The new status. Use one of the STATUS_* constants for easy use.
     */
    public function setOnlineStatus($status = null)
    {
        sql_helper::simpleQuery("UPDATE " . self::$sourceTable . " WHERE " . $this->getSelfCondition());
    }
    
    /**
     * Queries the user's current online status.
     * \return The online status ID. Query the database for the textual representation, if need be.
     */
    public function getOnlineStatus()
    {
        return sql_helper::simpleQuery("SELECT statusKey FROM " . self::$sourceTable . " WHERE " . $this->getSelfCondition());
    }
}

?>
