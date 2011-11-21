<?php
require_once(__DIR__ . "/device.class.inc");
require_once(__DIR__ . "/childDevice.func.inc");
class DeviceHandler
{
	private $id;
	private $device;
	
	public function __construct($ID)
	{
		$this->device = GirafDevice::getDevice($ID);
		$this->id = $ID; 
	}

	public function getDeviceId()
	{
		return $this->id;
	}
	
	public function getOwnerId()
	{
		$temp = $this->device;
		return $temp->ownerId;
	}
	
	public function getDeviceIdent()
	{
		$temp = $this->device;
		return $temp->deviceIdent;
	}
	
	public function getAllAppsOnDevice()
	{
		$AppsOnDevice = GirafDevice::getAppsOnDevice($this->id);
		return $AppsOnDevice;
	}
	public function connectAppToDevice($applicationKey)
	{
		$result = ChildAndDevice::connectAppDevice($applicationKey, $this->id);
		return $result;
	}
} 

?>