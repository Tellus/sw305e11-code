<?php
require_once(__DIR__ . "/device.class.inc");
class DeviceHandler
{
	private $id;
	private $device;
	
	public function __construct($ID)
	{
		$this->device = GirafDevices::getDevice($ID);
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
	
} 

?>