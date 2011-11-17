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
	* find the child from ID
	* \param dette skal være et gyldigt childId 
	*/	
	public function __construct($ID)
	{
		$this->id = $ID;
		$this->child = GirafChild::getGirafChild($ID);
		$this->childAbilities = GirafChild::getChildsAbilities($ID);
	}
	
	
	
	//---------------get----------------\\ 
	public function getId()
	{
		return $this->id;
	}
	
	/**
	* get the childs name
	* \return Returns the childs name
	*/
	public function getChildName()
	{
		$temp = $this->child;
		return $temp->profileName;
	}
	
	/**
	* \return Returns the childs age
	*/
	public function getChildAge()
	{
		$temp = $this->child;
		return $temp->profileBirthday;
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
		$temp = $this->child;
		$temp->__set('profileName', $value);
	}
	
	/**
	* \param Takes the birthday of the child 
	*/	
	public function setChildAge($value)
	{
		$temp = $this->child;
		$temp->__set('profileBirthday', $value);
	}
	
	public function setAbilities($newValue)
	{
		$this->oldChildAbilities = Array();
		$this->oldChildAbilities = $this->childAbilities;
		$this->childAbilities=$newValue;
	}
	
	//--------------save-----------------\\

	/**
	* 
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