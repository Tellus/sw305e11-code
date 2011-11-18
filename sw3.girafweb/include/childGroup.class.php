<?php

require_once(__DIR__ . "/group.class.inc"); 
/**
* this class handles requist from the interface about the child groups 
*/
class childGroups
{
	private $id;
	private $group = Array();
	private $children = Array();
	private $intersectionApps;
	
	/**
	* init the class using a group ID
	* \param a group ID
	*/
	public function __construct($gID)
	{
		$this->id = $gID;
		$this->group = GirafGroup::getGirafGroup($gID);
		$this->children = GirafGroup::getChildrenInGroup($gID);
	}
	
	
	//--------------getters-----------------\\
	/**
	*\return the group ID
	*/
	public function getGroupId()
	{
		return $this->id;
	}
	

	
}

?>