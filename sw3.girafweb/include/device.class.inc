<?php

require_once(__DIR__ . "/record.class.inc"); // Parent class
require_once(__DIR__ . "/application.class.inc"); // App class. Referenced.
require_once(__DIR__ . "/constants.inc"); // Table constants 

/** 
 * Class used to handle data of devices
 */
class GirafDevice extends GirafRecord
{
	public static function getSourceTable()
	{
		return TABLE_DEVICE;
	}
	
	public static function getPrimaryKey()
	{
		return "deviceId";
	}
	
	public static function getDevice($dId)
	{
		return parent::getInstance($dId);
	}
	
	public static function getDevices($cond = null, $return_as = self::RETURN_PRIMARYKEY)
	{
		return parent::getRecords($cond, $return_as);
	}
	
	public static function setAssociations()
	{
		/**
		 * Devices are upwards associated with children and downwards
		 * with apps.
		 **/
		self::addAssociation("GirafChild", TABLE_DEVICE, self::getPrimaryKey(), "ownerId");
		self::addAssociation("GirafApplication", TABLE_DEVICE_APPS, "deviceKey", "applicationKey");
	}
	
	/**
	 * Function used for getting applications related to a specific device.
	 * \param $deviceId The id of the device you wish to locate applications at.
	 * \return Returns an array of applicationsid's which is installed at the input device.
	 */
	public static function getAppsOnDevice($deviceId)
	{
		$sql = "SELECT applicationKey FROM " . TABLE_DEVICE_APPS . " WHERE deviceKey=$deviceId";
		
		// var_dump($sql);
		
		$result = sql_helper::selectQuery($sql);
		
		if(!$result)
		{
			var_dump($result, sql_helper::getErrMsg());
			return false;
		}
		
		$appsIds = Array();
	
		while ($row = $result->fetch_assoc())
		{
			$appsIds[] = $row["applicationKey"];
		}
		
		return $appsIds;
	}
	
	public function getApps($returnAs = self::RETURN_PRIMARYKEY)
	{
		return static::getChildRecords("GirafApplication", $returnAs);
	}	
}

?>
