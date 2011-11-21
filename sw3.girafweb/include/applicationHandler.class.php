<?php
require_once(__DIR__ . "/applications.class.inc"); 

class applicationHandler
{
	private id;
	private application;
	
	public function __construct($ID)
	{
		$this->application = GirafApplications::getDevicesWithInstalledApp($ID);
		$this->id = $ID;
	}
} 


?>