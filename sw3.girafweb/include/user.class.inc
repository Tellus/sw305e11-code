<?php

require_once(__DIR__ . "/record.class.inc");
require_once(__DIR__ . "/constants.inc");
require_once(__DIR__ . "/group.class.inc");

/**
 * Data container class for a user of the GIRAF system. This is typically
 * parents, kindergarten eachers and admins.
 */
class GirafUser extends GirafRecord
{
    /**
     * The user is currently online and visible.
     */
    const STATUS_ONLINE = 0;
    
    /**
     * The user is offline.
     */
    const STATUS_OFFLINE = 1;
    
    /**
     * The user is away.
     */
    const STATUS_AWAY = 2;
    
    /**
     * The user is busy.
     */
    const STATUS_BUSY = 3;
    
    /**
     * The user is online but invisible.
     */
    const STATUS_HIDDEN = 4;
    
    public static function getSourceTable()
    {
        return TABLE_USERS;
    }
    
    public static function getPrimaryKey()
    {
        return "userId";
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
        $sql = "UPDATE ". self::getSourceTable() . " SET statusKey=$status WHERE {$this->getSelfCondition()}";
        // echo $sql;
        $res = sql_helper::updateQuery($sql);
        if (!$res) die(sql_helper::getErrMsg());//return false;
        else return true;
    }
    
    /**
     * Queries the user's current online status.
     * \return The online status ID. Query the database for the textual representation, if need be.
     */
    public function getOnlineStatus()
    {
        $sql = "SELECT statusKey FROM " . self::getSourceTable() . " WHERE {$this->getSelfCondition()}";
        // echo $sql;
        return sql_helper::simpleQuery($sql);
    }
    
    /**
     * Retrieves a list of the groups that this user is a member of.
     * \return Array of groupIds.
     */
    public function getUserGroups()
    {
        $sql = "SELECT groupKey FROM " . TABLE_USER_GROUP_KEYS . " WHERE userKey=" . $this->id;
        // echo $sql;
        $keys = sql_helper::selectQuery($sql);
        
        // We shouldn't expect this error.
        if(!$keys) die(sql_helper::getErrMsg());
        
        $ret = array();
        while($row = $keys->fetch_row())
        {
            $ret[] = $row[0];
        }
        
        return $ret;
    }
    
    /**
     * Adds the current user to a group.
     * \param Either name or ID of the group that the user is to be added to.
     * \return True on success, false otherwise.
     * \throws Exception If the group does not exist or the user has not yet
     * been added to the database, an exception is thrown.
     */
    public function addToGroup($gId)
    {
        // First, make sure the group exists. We do this indirectly by creating
        // a GirafGroup instance or getting the id based on name.
        if (is_numeric($gId))
            $g = GirafGroup::getGirafGroup($gId);
        else
        {
            $gk = sql_helper::simpleQuery("SELECT " . GirafGroup::getPrimaryKey() . " FROM " . TABLE_GROUPS . " WHERE groupName='$gId'");
            $g = GirafGroup::getGirafGroup($gId);
        }
        
        if ($g == null || $g == false) throw new Exception("Bad group id.");
    
        $sql = "INSERT INTO " . TABLE_USER_GROUP_KEYS . " (groupKey, userKey) VALUES ({$g->id}, {$this->id})";
        
        $result = sql_helper::insertQuery($sql);
        
        if (!$result) throw new Exception(sql_helper::getErrMsg());
    }
    
    /**
     * Removes a child from a group.
     * \todo Implement.
     * */
    public function removeFromGroup($gId)
    {
    
    }
    
    /**
     * Retrieves array of applications installed on the child's devices.
     * \param $dev Optional. Set a single device to pull apps for.
     * \param $returnAs Define return type, array of app objects or
     * array of primary keys.
     * */
    public function getApps($dev = null, $returnAs = self::RETURN_PRIMARYKEY)
    {
		
	}
    
    
}

?>
