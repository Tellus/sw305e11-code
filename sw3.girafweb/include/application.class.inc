<?php

require_once(__DIR__ . "/record.class.inc"); //parent class
require_once(__DIR__ . "/constants.inc"); // table constants

/**
* Class used for handeling application data
*/
class GirafApplications extends GirafRecord
{
	public static function getSourceTable()
	{
		return TABLE_APPS;
	}

	public static function getPrimaryKey()
	{
		return "applicationId";
	}
	
	public static function getApplication($aId)
	{
		return parent::getInstance($aId);
	}
	
	public static function getApplications($cond = null, $return_as = self::RETURN_PRIMARYKEY)
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
