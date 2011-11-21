<?php
require_once(__DIR__ . "/application.class.inc"); 
require_once(__DIR__ . "/childDevice.func.inc");


/**
* This class can contain one application and functions 
*/
class applicationHandler
{
	private $id;
	private $application;
	
	/**
	* Make an instant of an application 
	* \param An application ID
	*/
	public function __construct($ID)
	{
		$this->application = GirafApplication::getApplication($ID);
		$this->id = $ID;
	}
	
	/**
	* \return the apps id no
	*/
	public function getAppId()
	{
		return $this->id;
	}
	
	/**
	* \return the app's name
	*/
	public function getApplicationName()
	{
		$temp = $this->application;
		return $temp->applicationName;
	}

	/**
	* \return the app's desciption
	*/	
	public function getApplicationDescription()
	{
		$temp = $this->application;
		return $temp->applicationDescription;
	}
	
	/**
	* \return The name of the package
	*/
	public function getPackage()
	{
		$temp = $this->application;
		return $temp->package;
	}
	
	/**
	* return The version of the package, described as a numeric.
	*/
	public function getVersion()
	{
		$temp = $this->application;
		return $temp->version;
	}
	
	/**
	* \return the version of the package, described as a string?
	*/
	public function getVersionString()
	{
		$temp = $this->application;
		return $temp->versionString;
	}
	
	/**
	* \return State of the package. 
	*/
	public function getState()
	{
		$temp = $this->application;
		return $temp->state;
	}
	/**
	* return the adminId Administrator of the package.
	*/
	public function getAdminId()
	{
		$temp = $this->application;
		return $temp->adminId;
	}

	/**
	* Get an array of the application's abilities
	* \return An array of the application's abilities 
	*/
	public function getAbilities()
	{
		$abilities = Array();
		$temp = $this->application;
		
		$abilities['$CanDragAndDrop'] = $temp->canDragAndDrop;
		$abilities['$canHear'] = $temp->canHear;
		$abilities['$requiresSimpleVisualEffects'] = $temp->requiresSimpleVisualEffects;
		$abilities['$canAnalogTime'] = $temp->canAnalogTime;
		$abilities['$canDigitalTime'] = $temp->canDigitalTime;
		$abilities['$canRead'] = $temp->canRead;		
		$abilities['$hasBadVision'] = $temp->hasBadVision;		
		$abilities['$requiresLargeButtons'] = $temp->requiresLargeButtons;		
		$abilities['$canSpeak'] = $temp->canSpeak;
		$abilities['$canNumbers'] = $temp->canNumbers;
		$abilities['$canUseKeyboard'] = $temp->canUseKeyboard;	
		return $abilities;
	}	
	
	/**
	* Find all the device id's that have this app installed 
	* \return An array of deviceid's at which the input application is installed or false if none found
	*/
	public function getDevicesWithInstalledApp()
	{
		$application = GirafApplication::getDevicesWithInstalledApp($this->id);
		if(!$application)
		{
			return false;
		}
		return $application;
	}
	
	/**
	* connects an app to a device
	* \param Needs a device id
	* \return Returns TRUE if succesful, FALSE otherwise
	*/
	public function connectAppToDevice($deviceKey)
	{
		$result = ChildAndDevice::connectAppDevice($this->id, $deviceKey);
		if(!$result)return false;
		return $result;
	}

} 
?>
