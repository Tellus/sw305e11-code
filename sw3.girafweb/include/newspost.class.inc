<?php

require_once(__DIR__ . "/record.class.inc");

class GirafNewsPost
{
    function __construct()
    {
        parent::__construct(TABLE_NEWSPOSTS, "GirafNewsPost");
    }
    
    /**
     * Retrieves all news related to a particular group.
     * \param $gId ID of the group whos news is requested. If omitted or set to 
     * the default group, site news is retrieved instead.
     * \param $return_as Set a desired return type.
     * \return A set of news for the given group.
     * \throws Exception If the requested group or return value is invalid.
     * \sa GirafRecord::getRecords()
     * \sa GirafRecord::RETURN_PRIMARYKEY
     * \sa GirafRecord::RETURN_RECORD
     */
    public static function getGroupNews($gId = null, $return_as = self::RETURN_PRIMARYKEY)
    {
        // If group id was not set, set it to the id of the group "Users", our default group that all users belong to.
        if (!isset($gId)) $gId = sql_helper::simpleQuery("SELECT " . self::$primaryKey . " FROM " . self::$sourceTable . " WHERE groupName='Users'");
        // Pass the remainder of execution to the base class.
        return parent::getRecords("groupKey=$gId", $return_as);
    }
    
    /**
     * Retrieves all relevant news for a particular user.
     * \param $uId The user you want news for. If omitted, the user in the
     *              current session will be attempted.
     * \param $return_as The requested return type.
     * \return The requested record set or false if the user was omitted and no
     *         user was in session.
     * \sa GirafRecord::getRecords()
     * \sa GirafRecord::RETURN_PRIMARYKEY
     * \sa GirafRecord::RETURN_RECORD
     */
    public static function getUserNews($uId = null, $return_as = self::RETURN_PRIMARYKEY)
    {
        if (!isset($uId))
        {
            if (!isset(GirafSession::$userId)) return false;
        }
        
        // Retrieve the user so we can get a simple list of group keys.
        GirafUser $user = GirafUser::getUser($uId);
        
        // If group id was not set, set it to the id of the group "Users", our default group that all users belong to.
        if (!isset($gId)) $gId = sql_helper::simpleQuery("SELECT " . self::$primaryKey . " FROM " . self::$sourceTable . " WHERE groupName='Users'");
    }
}

?>
