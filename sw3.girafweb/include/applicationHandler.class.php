<?php
require_once(__DIR__ . "/application.class.inc"); 

class applicationHandler
{
	private $id;
	private $application;
	
	public function __construct($ID)
	{
		$this->application = GirafApplication::getApplication($ID);
		$this->id = $ID;
	}
	
	public function getAppId()
	{
		return $this->id;
	}
	
	public function getApplicationName()
	{
		$temp = $this->application;
		return $temp->applicationName;
	}
	
	public function getApplicationDescription()
	{
		$temp = $this->application;
		return $temp->applicationDescription;
	}
	
	public function getPackage()
	{
		$temp = $this->application;
		return $temp->package;
	}
	
	public function getVersion()
	{
		$temp = $this->application;
		return $temp->version;
	}
	
	public function getVersionString()
	{
		$temp = $this->application;
		return $temp->versionString;
	}
	
	public function getState()
	{
		$temp = $this->application;
		return $temp->state;
	}
	
	public function getAdminId()
	{
		$temp = $this->application;
		return $temp->adminId;
	}

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

} 
?>
