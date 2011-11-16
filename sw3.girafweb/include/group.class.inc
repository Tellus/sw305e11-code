<?php

require_once(__DIR__ . "/record.class.inc");

/**
 * Data container class for group data.
 */
class GirafGroup extends GirafRecord
{
    public function getSourceTable()
    {
        return TABLE_GROUPS;
    }
    
    public function getPrimaryKey()
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
	
	public static function getChildrenInGroup($groupId)
	{
		$result = sql_helper::selectQuery("SELECT childKey FROM" . TABLE_CHILD_GROUP . "WHERE groupKey=" . $groupId);
		
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
