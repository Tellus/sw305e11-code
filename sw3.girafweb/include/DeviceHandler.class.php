<?php
require_once(__DIR__ . "/device.class.inc");
require_once(__DIR__ . "/childDevice.func.inc");

/**
* A class used to handle data of devices and useful functions
*/
class DeviceHandler
{
	private $id;
	private $device;
	
	/**
	* Create an object of a device
	* \param Needs a valid device id  
	*/
	public function __construct($ID)
	{
		$this->device = GirafDevice::getDevice($ID);
		$this->id = $ID; 
	}

	/**
	* Gets the device id
	*\return The device id
	*/
	public function getDeviceId()
	{
		return $this->id;
	}
	
	/**
	* Gets the device owner id which is equal to the child's id
	*\return The owner/child's id
	*/
	public function getOwnerId()
	{
		$temp = $this->device;
		return $temp->ownerId;
	}

	/**
	* Gets the device identity. This is currently defined as an MD5 of the device's WiFi MAC address.
	*\return The device's identity
	*/	
	public function getDeviceIdent()
	{
		$temp = $this->device;
		return $temp->deviceIdent;
	}
	
	/**
	* Function used for getting applications related to this device.
	* \return Returns an array of applicationsid's which is installed at the input device if succesful and false otherwise.
	*/
	public function getAllAppsOnDevice()
	{
		$AppsOnDevice = GirafDevice::getAppsOnDevice($this->id);
		if(!$AppsOnDevice) return false;
		return $AppsOnDevice;
	}
	
	/**
	* Connect an app and device
	* \param An application's id
	* \return True if succesful and false otherwise
	*/
	public function connectAppToDevice($applicationKey)
	{
		$result = ChildAndDevice::connectAppDevice($applicationKey, $this->id);
		if(!$result) return false;
		return $result;
	}
} 

?>