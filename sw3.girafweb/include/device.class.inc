<?php

require_once(__DIR__ . "/record.class.inc"); // Parent class
require_once(__DIR__ . "/constants.inc"); // Table constants 

/** 
 * Class used to handle data of devices
 */
class GirafDevices extends GirafRecord
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
		return parent::getRecord($cond, $return_as);
	}
	
		/**
	* Function used for getting applications related to a specific device.
	* \param $deviceId The id of the device you wish to locate applications at.
	* \return Returns an array of applicationsid's which is installed at the input device.
	*/
	public static function getAppsOnDevice($deviceId)
	{
		$result = sql_helper::selectQuery("SELECT applicationKey FORM" . TABLE_RELATIONS . "WHERE deviceKey=" . $deviceId);
		
		if(!result) return false;
		
		$appsIds = Array();
		$iter = 0;
	
		while ($row = $result->fetch_assoc())
		{
			$appsIds[iter] = $row["applicationsKey"];
			
			$iter++;
		}
		
		return $appsIds;
	}
	
	
}

?>