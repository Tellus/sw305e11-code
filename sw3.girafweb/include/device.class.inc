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
	* Function used for finding the devices a specific applications is installed at
	* \param $appId The id of the specific application
	* \return Returns an array of deviceid's at which the input application is installed
	*/
	
	public static function getDevInstalledApps($appId)
	{
		$result = sql_helper::selectQuery("SELECT deviceKey FROM" . TABLE_RELATIONS . "WHERE applicationKey=" . appId);
		
		if(!$result) return false;
		
		$devIds = Array();
		
		$iter = 0;
		
		while($row = $result->fetch_assoc())
		{
			$devIds[iter] = $row["deviceKey"];
			
			$iter++;
		}
		
		return $devIds;
	}
}

?>