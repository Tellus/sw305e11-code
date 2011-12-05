<?php

require_once(__DIR__ . "/record.class.inc");

/**
 * Data container class for group data.
 */
class GirafGroup extends GirafRecord
{
    public static function getSourceTable()
    {
        return TABLE_GROUPS;
    }
    
    public static function getPrimaryKey()
    {
        return "groupId";
    }
    
    public static function getGirafGroup($gId)
    {
        return parent::getInstance($gId);
    }
    
    public static function getGirafGroups($cond = null, $return_as = self::RETURN_PRIMARYKEY)
    {
        return parent::getRecords($cond, $return_as);
    }
	
	public static function setAssociations()
	{
		/**
		 * Groups are associated downwards with users and children.
		 **/
		self::addAssociation("GirafUser", TABLE_USER_GROUP_KEYS, "groupKey", "userKey");
		self::addAssociation("GirafChild", TABLE_CHILD_GROUP_KEYS, "groupKey", "childKey");
	}
	
	/**
	 * Retrieves all children associated with the current group.
	 * \param $returnAs A GirafRecord return type constant.
	 * \return Array of $returnAs objects.
	 * */
	public function getChildren($returnAs = GirafRecord::RETURN_PRIMARYKEY)
	{
		return static::getChildRecords("GirafChild", $returnAs);
	}
	
	/**
	 * Retrieves all children contained in a specific group.
	 * \param $groupId Group's ID.
	 * \return Array of child ID's.
	 * */
	public static function getChildrenInGroup($groupId)
	{
		$result = sql_helper::selectQuery("SELECT childKey FROM" . TABLE_CHILD_GROUP_KEYS . "WHERE groupKey=" . $groupId);
		
		if(!$result) return false;
		
		$profilIds = Array();
		
		$iter = 0; 
		
		while ($row = $result->fetch_assoc())
		{
			$profilIds[iter] = $row["childKey"];
			
			$iter++;
		}
		
		return $profilIds;
	}
}

?>
