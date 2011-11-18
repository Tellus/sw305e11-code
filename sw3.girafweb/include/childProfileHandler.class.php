<?php
require_once(__DIR__ . "/record.class.inc");
require_once(__DIR__ . "/child.class.inc"); 

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
	* \param dette skal være et gyldigt childId 
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
	* Returning the child's idkey
	* \return the child's idkey 
	*/
	public function getId()
	{
		return $this->id;
	}
	
	/**
	* Returning the child's  name
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
	* Returning an array for abilities where the index key is the ability and the value is a bool
	* \return an array with booleans indicating the child's abilities
	*/
	public function getAbilitiesArray()
	{
		return $this->childAbilities;
	}
	

	//---------------set-----------------\\
	
	/**
	* Change the child's name 
	* \param Takes the name of the child 
	*/
	public function setChildName($value)
	{
		$temp = $this->child;
		$temp->__set('profileName', $value);
	}
	
	/**
	* Change the child's birthday 
	* \param Takes the birthday of the child 
	*/	
	public function setChildBirthday($value)
	{
		$temp = $this->child;
		$temp->__set('profileBirthday', $value);
	}
	
	/**
	* Change the child's abilities which is in a array
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
	* Save all the changes that have been made
	*/
	public function saveChanges()
	{
		if($this->oldChildAbilities != "")
		{   
			$child->commitAbilityChange($this->id, $this->oldChildAbilities, $this->childAbilities);
		}
		
		$child->commit();
	}
	//--------------others----------------\\

	
	
}

?>