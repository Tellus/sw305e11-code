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
	* Create a new child
	* \param The child's name
	* \param The child's birthday as YYYY-MM-DD(2000-12-29)
	* \param An array of the child's abilities
	* \returns The new child's ID upon succes
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
	* Get a child's id from the database, identified from the child's name and birthday
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
	* This function is called by ChildAndDevice::createChild to save the child in the database
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
	* Creates a new device in the system
	* \param The child's id 
	* \param The device identity
	* \return The device id on success
	*/
	public static function createDevice($childID, $deviceIdent)
	{
		self::createNewDevice($childID, $deviceIdent);
		return self::deviceId($childID, $deviceIdent);
	}
 
	/**
	* This function is used by ChildAndDevice::createDevice to create a new device in the database
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
	* Finds a device id from the child's id and device identity 
	* \param The child's id 
	* \param The device identity
	* \return The device id on success and false otherwise
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
 

	/**
	* Try to connect an user and a child 
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
	
	/**
	* Try to connect an app and device
	* \param the app's id
	* \param the device's id
	* \return true if succesful and false otherwise
	*/
	public static function connectAppDevice($applicationKey, $deviceKey)
	{
		$sql = "INSERT INTO " . TABLE_DEVICE_APPS ." (applicationKey, deviceKey) VALUES ('$applicationKey','$deviceKey')";
				$result = sql_helper::insertQuery($sql);
			
		if (!$result)
		{
			return false;
		}

		return $result;
	}
 }
 
 ?>