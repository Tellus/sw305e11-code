<?php
require_once(__DIR__ . "/sql_helper.inc");
require_once(__DIR__ . "/constants.inc");
require_once(__DIR__ . "/child.class.inc");

/* This file contains functions that have specialised data retrieval
 * purposes.
 */
class ChildAndDevice
{
	/**
	* \param The child's name
	* \param The child's birthday as YYYY-MM-DD
	* \param An array of the child's abilities
	* \returns The new childs ID
	*/
	public static function createChild($profileName, $profileBirthday, $abilities)
	{
		self::createNewChild($profileName, $profileBirthday);
		$childkey = self::getChildId($profileName, $profileBirthday);
		$allAbilities = GirafChild::getAllAbilities();
		GirafChild::commitAbilityChange($childkey, $allAbilities, $abilities);
		return $childkey;	
	}
	
	/**
	* \param The child's name
	* \param The child's birthday
	* \returns The child's id if succesful
	*/
	public static function getChildId($profileName, $profileBirthday)
	{
		$result = sql_helper::selectQuery("SELECT profileId FROM " . TABLE_CHILDREN . " WHERE profileName = '$profileName' AND profileBirthday='$profileBirthday'"); //.$profileName);
		if ($row = $result->fetch_assoc()) 
		{
			return $row["profileId"];
		}
		return null;
	}

	/**
	* create a new child and returns the child's id
	* \return True if succesful
	*/
	private static function createNewChild($profileName, $profileBirthday)
	{
		$sql = "INSERT INTO " . TABLE_CHILDREN . " (profileName, profileBirthday) VALUES ('$profileName','$profileBirthday')";
		$result = sql_helper::insertQuery($sql);
			
		if (!$result)
		{
			return false;
		}

		return $result;
	}
	
	/**
	* \param the child's id 
	* \param the device identity
	* \return the device id on success
	*/
	public static function createDevice($childID, $deviceIdent)
	{
		self::createNewDevice($childID, $deviceIdent);
		return deviceId($childID, $deviceIdent);
	}
 
	/**
	* \param the child's id 
	* \param the device identity
	* \return true on succes otherwise false
	*/
	private static function createNewDevice($childID, $deviceIdent)
	{
		$sql = "INSERT INTO " . TABLE_DEVICE . " (ownerId, deviceIdent) VALUES ('$childID','$deviceIdent')";
		$result = sql_helper::insertQuery($sql);
			
		if (!$result)
		{
			return false;
		}

		return result;
	}
	
	/**
	* \param the child's id 
	* \param the device identity
	* \return the device id on success
	*/
	public static function deviceId($childID, $deviceIdent)
	{
		$result = sql_helper::selectQuery("SELECT deviceId  FROM " . TABLE_DEVICE . " WHERE ownerId = '$childID' AND deviceIdent='$deviceIdent'"); 
		if ($row = $result->fetch_assoc()) 
		{
			return $row["deviceId"];
		}
		return null;
	}
 
 //connect child with user(childKey, userKey)
	/**
	* \param the child's id
	* \param the user's id
	* \return true if succesful
	*/
	public static function connectChildAndUser($childId, $userId)
	{
		$sql = "INSERT INTO " . TABLE_CHILD_USER . " (childKey, userKey) VALUES ('$childId','$userId')";
		$result = sql_helper::insertQuery($sql);
			
		if (!$result)
		{
			return false;
		}

		return $result;
	}
	
 
//connect app device(applicationKey, deviceKey)
 }
 
 ?>