<?php
require_once(__DIR__ . "/record.class.inc");
require_once(__DIR__ . "/child.class.inc"); 

/**
* This class handles requist from the interface about the Child 
*/
class childProfile
{
	const CAN_DRAG_AND_DROP = "canDragAndDrop";
	const CAN_HEAR = "canHear";
	const CAN_ANALOG_TIME = "canAnalogTime";	
	const CAN_DIGITAL_TIME = "canDigitalTime";
	const CAN_READ = "canRead";
	const REQURE_LARGE-_BUTTONS = "requiresLargeButtons";
	const CAN_SPEAK = "canSpeak";
	const CAN_NUMBERS = "canNumbers";
	const CAN_USE_KEYBOARD = "canUseKeyboard";
	const HAS_BAD_VISION = "hasBadVision";
	const REQURE_SIMPLE_VE = "requireSimpleVisualEffects";
	
	private $child;
	private $childAbilities = Array();


	

	/**
	* find the child from ID
	* \param dette skal være et gyldigt childId 
	*/	
	public function __construct($ID)
	{
		$this->child = GirafChild::getGirafChild($ID);
		$this->childAbilities = GirafChild::getChildsAbilities($ID);
	}
	
	
	
	//---------------get----------------\\ 
	
	/**
	* get the childs name
	* \return Returns the childs name
	*/
	public function getChildName()
	{
		return $child->profileName;
	}
	
	/**
	* \return Returns the childs age
	*/
	public function getChildAge()
	{
		return $child->profileBirthday;
	}
	
	/**
	*
	*/
	public function getAbilitiesArray()
	{
		return $this->childAbilities;
	}
	

	//---------------set-----------------\\
	
	/**
	* \param Takes the name of the child 
	*/
	public function setChildName($value)
	{
		$child->__set('profileName', $value);
	}
	
	/**
	* \param Takes the birthday of the child 
	*/	
	public function setChildAge()
	{
		$child->__set('profileBirthday', $value);
	}
	
	public function setCanDragAndDrop($newValue)
	{
		$this->childAbilities["CAN_DRAG_AND_DROP"] = $newValue;
		$this->abilityChange = true;
	}
	
	
	$key=>$value
	//--------------save-----------------\\

	/**
	* 
	*/
	public function saveChanges()
	{
		$child->commit();
	}
	//--------------others----------------\\

	
	
}

?>