<?php

require_once(__DIR__ . "/record.class.inc"); // Base class.
require_once(__DIR__ . "/constants.inc"); // Table constants.

/**
 * Data container class for a child in the system.
 */
class GirafChild extends GirafRecord
{
    /**
     * Gets the first device associated with the child.
     * \return A GirafDevice associated with the child, or null.
     * \todo{Write the function}
     */
    public function getDevice()
    {
        return null;
    }
    
    function __construct()
    {
        parent::__construct(TABLE_CHILDREN, "GirafChild");
    }
    
    /**
     * Creates a new GirafChild instance with the data found under the given
     * id.
     * \param childId ID of the child we're retrieving.
     */
    public static function getGirafChild($childId)
    {
        return parent::getInstance($childId);
    }
	
	public static function getChildren($groupId)
	{
		$result = sql_helper::selectQuery("SELECT childKey FROM" . TABLE_CHILD_GROUP . "WHERE groupKey=" . $groupId);
		
		if(!$result) return false
		
		$profilIds = array();
		
		$iter = 0; 
		
		while ($row = $result->fetch_assoc())
		{
			$profilIds[iter] = $row["childKey"];
			
			$iter++;
		}
	}
}

?>
