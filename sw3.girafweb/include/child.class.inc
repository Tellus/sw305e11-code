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
    
    public function getPrimaryKey()
    {
        return "profileId";
    }
    
    public function getSourceTable()
    {
        return TABLE_CHILDREN;
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
	
	public static function getChildsAbilities($childId)
	{
		$result = sql_helper::selectQuery("SELECT definitionName FROM kidsProfile_abilities WHERE profileKey=$childId");
		$allArray = GirafChild::getAllAbilities();
        if(!$result) return null; //error
		
		$resultArray = Array();
		$iter=0;		
		while ($row = $result->fetch_assoc())
		{
			$resultArray[$iter] = $row["definitionName"];
			$iter++;
		}
		
		foreach($allArray as $key=>$value)
		{
			$isInArray = array_search($key, $resultArray);
			if(!($isInArray==false))
			{
				$value = true;
			}
		}
		return $resultArray;
	}
	public static function getAllAbilities()
	{
		$result = sql_helper::selectQuery("SELECT * FROM  abilityDefinitions");
	
        if(!$result) return null; //error
		
		$resultArray = Array();    
		while ($row = $result->fetch_assoc())
		{
			$resultArray[$row["definitionName"]] = false;
		}
		return $resultArray;
	}

}

?>
