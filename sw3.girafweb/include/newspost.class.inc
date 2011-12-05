<?php

require_once(__DIR__ . "/record.class.inc");
require_once(__DIR__ . "/constants.inc");
require_once(__DIR__ . "/group.class.inc");

class GirafNewsPost extends GirafRecord
{
    public static function getPrimaryKey()
    {
        return "newsId";
    }
    
    public static function getSourceTable()
    {
        return TABLE_NEWSPOSTS;
    }
    
    public static function setAssociations()
    {
		// Has no implementation.
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
        if (!isset($gId))
        {
			$sql = "SELECT " . GirafGroup::getPrimaryKey() . " FROM " . GirafGroup::getSourceTable() . " WHERE groupName='Users'";
        
			$gId = sql_helper::simpleQuery($sql);
		}
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
			$s = GirafSession::getSession();
            $uId = $s->getCurrentUser();
            if ($uId === null) return false;
        }
        
        // Retrieve the user so we can get a simple list of group keys.
        $user = GirafUser::getGirafUser($uId);
        
        // Get all of the users' groups now. This is through the keys.
        $sql = "SELECT groupKey FROM " . TABLE_USER_GROUP_KEYS . " WHERE userKey=$uId";
        
        $groups = sql_helper::selectQuery($sql);
        
        // Prep the return array-
        $news_ids = array();
        
        // We traverse each group, each time retrieving all its news and placing
        // in the key return array.
        while($group = $groups->fetch_row())
        {
            $tmp_news = self::getGroupNews($group[0], $return_as);
            $news_ids = array_merge($news_ids, $tmp_news);
        }
        
        return $news_ids;
    }
}

?>
