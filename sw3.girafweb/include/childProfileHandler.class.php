<?php
require_once(__DIR__ . "/record.class.inc");
require_once(__DIR__ . "/child.class.inc"); 
require_once(__DIR__ . "/childDevice.func.inc");

/**
* This class handles requist from the interface about the Child 
*/
class childProfile
{

	private $id;
	private $child;
	private $oldChildAbilities;
	private $childAbilities = Array();



	

	/**
	* Find the child from ID
	* \param This has to be an valid child ID 
	*/	
	public function __construct($ID)
	{
		$this->id = $ID;
		$this->child = GirafChild::getGirafChild($ID);
		$temp = $this->child;
		$this->childAbilities = $temp->getChildsAbilities($ID);
	}
	
	
	
	//---------------get----------------\\ 
	/**
	* Get the child's id
	* \return The child's idkey 
	*/
	public function getId()
	{
		return $this->id;
	}
	
	/** 
	* Get the child's  name
	* \return Returns the childs name
	*/
	public function getChildName()
	{
		$temp = $this->child;
		return $temp->profileName;
	}
	
	/**
	* Returning the child birthday
	* \return Returns the childs age
	*/
	public function getChildBirthday()
	{
		$temp = $this->child;
		return $temp->profileBirthday;
	}
	
	/**
	* Get the array for child's abilities where the index key is the ability and the value is a bool
	* \return an array with booleans indicating the child's abilities
	*/
	public function getAbilitiesArray()
	{
		return $this->childAbilities;
	}
	

	//---------------set-----------------\\
	
	/**
	* Change the child's name, but remember to commit otherwise these changes woundn't be saved in the database 
	* \param Takes the name of the child 
	*/
	public function setChildName($value)
	{
		$temp = $this->child;
		$temp->__set('profileName', $value);
	}
	
	/**
	* Change the child's birthday, but remember to commit otherwise these changes woundn't be saved in the database 
	* \param Takes the birthday of the child 
	*/	
	public function setChildBirthday($value)
	{
		$temp = $this->child;
		$temp->__set('profileBirthday', $value);
	}
	
	/**
	* Change the child's abilities which is in a array, but remember to commit otherwise these changes woundn't be saved in the database
	* \param Takes an array where index key is the ability and the value is a bool
	*/
	public function setAbilities($newValue)
	{
		$this->oldChildAbilities = Array();
		$this->oldChildAbilities = $this->childAbilities;
		$this->childAbilities=$newValue;
	}
	
	//--------------save-----------------\\

	/**
	* Save all the changes in the database that have been made by the user
	* \return Returns true if succesful and false otherwise 
	*/
	public function saveChanges()
	{
		$temp = $this->child;
		if($this->oldChildAbilities != "")
		{
			$resultAbilities = $temp->commitAbilityChange($this->id, $this->oldChildAbilities, $this->childAbilities);
			if(!$resultAbilities) return false;
		}
		$result=$temp->commit();
		if(!$result) return false;
		return $result;
	}
	//-------------others------------\\
	
	/**
	* This function creates a new device which has this child's id as ownerID  
	* \param The device identity
	* \return The device id if succesful and false otherwise
	*/
	public function createNewAndConnectDevice($deviceIdent)
	{
		$deviceId = ChildAndDevice::createDevice($this->id, $deviceIdent);
		if(!$deviceId) return false;
		return $deviceId;
		
	}
}

?>