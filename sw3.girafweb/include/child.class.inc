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
    public static function getDevice()
    {
        return null;
    }
    
    public static function getPrimaryKey()
    {
        return "profileId";
    }
    
    public static function getSourceTable()
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
		$result = sql_helper::selectQuery("SELECT definitionName FROM ". TABLE_KIDSPROFILE_ABILITIES ." WHERE profileKey=$childId");
		$allArray = GirafChild::getAllAbilities();
        if(!$result) return null; //error
		
		$resultArray = Array();
		$iter=1;		
		while ($row = $result->fetch_assoc())
		{
			$resultArray[$iter] = $row["definitionName"];
			$iter++;
		}
		
		foreach($allArray as $key=>$value)
		{
			$isInArray= 0;
			$isInArray = array_search($key, $resultArray);
			if(!($isInArray==false))
			{
				$allArray[$key] = true;
			}
		}

		return $allArray;
	}
	public static function getAllAbilities()
	{
		$result = sql_helper::selectQuery("SELECT * FROM ". TABLE_ABILITY_DEFINITIONS);
	
        if(!$result) return null; //error
		
		$resultArray = Array();    
		while ($row = $result->fetch_assoc())
		{

			$resultArray[$row["definitionName"]] = 0;
		}

		return $resultArray;
	}
	public static function commitAbilityChange($ID, $oldAbilities, $newAbilities)
	{
		$aDQuery = sql_helper::selectQuery("SELECT * FROM ". TABLE_ABILITY_DEFINITIONS);
		
		$abilityDefinition = Array();    
		while ($row = $aDQuery->fetch_assoc())
		{
			$abilityDefinition[$row["definitionId"]] = $row["definitionName"];
		}

		foreach($newAbilities as $key=>$value)
		{
			if(!($oldAbilities[$key] == $newAbilities[$key]))
			{

				$definitionKey = array_search($key, $abilityDefinition);
				if($newAbilities[$key]==true)
				{
					sql_helper::insertQuery("INSERT INTO " . TABLE_PROFILE_ABILITIES . " (profileKey, definitionKey) VALUES ('$ID', '$definitionKey')");
				}
				else
				{
					sql_helper::deleteQuery("DELETE FROM " . TABLE_PROFILE_ABILITIES . " WHERE profileKey = " . $ID ." AND definitionKey = " .$definitionKey . "");
					
				}
			}
		}
	}

}

?>
