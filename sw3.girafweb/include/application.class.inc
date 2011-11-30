<?php

require_once("record.class.inc"); //parent class
require_once("constants.inc"); // table constants

/**
* Class used for handeling application data
*/
class GirafApplication extends GirafRecord
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
	* Function used for finding the devices a specific applications is installed at
	* \param $appId The id of the specific application
	* \return Returns an array of deviceid's at which the input application is installed
	*/	
	public static function getDevicesWithInstalledApp($appId)
	{
	
		$result = sql_helper::selectQuery("SELECT deviceKey FROM " . TABLE_DEVICE_APPS . " WHERE applicationKey= '$appId'");
	
		if(!$result) return false;
		$devIds = Array();		
		$iter = 0;
		
		while($row = $result->fetch_assoc())
		{
			$devIds[$iter] = $row["deviceKey"];
			$iter++;
		}
		
		return $devIds;
	}
}

?>
