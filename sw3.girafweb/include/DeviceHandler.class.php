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
	
	
} 

?>