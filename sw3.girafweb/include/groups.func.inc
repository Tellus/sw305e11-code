<?php
 
// Need SQL access.
require_once("sql_helper.inc");

// Need table constants.
require_once("constants.inc");

/**
 * Contains functions specific for handling group-centric actions.
 */
class groups
{
    /**
     * Retrieves a group's immediate data and returns it.
     * \param groupId The ID of the group to retrieve.
     * \param returnAs The type of return data you'd like. Choices are "GROUP_INSTANCE",
     * which returns a GirafClass object, "RESULTSET" which returns the raw result set or
     * "ROW" which is the single returned row.
     */
    public static function getGroupData($groupId, $returnAs = "GROUP_INSTANCE")
    {
        $result = sql_helper::selectQuery("SELECT * FROM " . TABLE_GROUPS . " WHERE groupId=" . $groupId);
        
        // Result should be a MySQLi_Result object now.
        if (!$result) return false;
        
        switch($returnAs)
        {
            case "GROUP_INSTANCE":
                $row = $result->fetch_assoc();
                $group = new GirafGroup($row["groupId"], $row["groupName"], $row["adminKey"]);
                return $group;
                break;
            case "RESULTSET":
                return $result;
                break;
            case "ROW":
                return $result->fetch_assoc();
                break;
        }
        
        return false; // We should never reach this line.
    }
}
?>
