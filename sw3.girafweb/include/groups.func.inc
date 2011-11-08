<?php
 
// Need SQL access.
require_once(__DIR__ . "/sql_helper.inc");

// Need table constants.
require_once(__DIR__ . "/constants.inc");

/**
 * Contains functions specific for handling group-centric actions.
 */
class groups
{
    const RETURN_GROUP = "RETURN_GROUP";
    const RETURN_RESULTSET = "RETURN_RESULTSET";
    const RETURN_ROW = "RETURN_ROW";

    /**
     * Retrieves a group's immediate data and returns it.
     * \param groupId The ID of the group to retrieve.
     * \param returnAs The type of return data you'd like. Choices are "GROUP_INSTANCE",
     * which returns a GirafClass object, "RESULTSET" which returns the raw result set or
     * "ROW" which is the single returned row.
     */
    public static function getGroupData($groupId, $returnAs = self::RETURN_GROUP)
    {
        $result = sql_helper::selectQuery("SELECT * FROM " . TABLE_GROUPS . " WHERE groupId=" . $groupId);
        
        // Result should be a MySQLi_Result object now.
        if (!$result) return false;
        
        switch($returnAs)
        {
            case self::RETURN_GROUP:
                $row = $result->fetch_assoc();
                $group = GirafGroup::getGirafGroup($row["groupId"]);
                return $group;
                break;
            case self::RETURN_RESULTSET:
                return $result;
                break;
            case self::RETURN_ROW:
                return $result->fetch_assoc();
                break;
        }
        
        return false; // We should never reach this line.
    }

    /*
    * Retrieves groupId from the database table "groups", and returns it as an array
    * \return Returns an array of group id's
    */

    function getGroups()
    {
	    $result = sql_helper::selectQuery("SELECT groupId FROM " . TABLE_GROUPS );
	
	    if(!$result) return false;
	
	    $retval = array();
	
	    $iter = 0;
	
	    while($row = $result->fetch_assoc())
	    {
		    $retval[$iter] = $row["groupId"];
		
		    $iter++;
		
	    }
	    return $retval;
    }
}
?>
