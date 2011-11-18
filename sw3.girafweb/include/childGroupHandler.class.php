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
	* 
	*/
	public function __construct($gID)
	{
		$this->id = $gID;
		$this->group = GirafGroup::getGirafGroup($gID);
		getChildrenFromDB();
	}
	
	
	//--------------getters-----------------\\
	public function getGroupId()
	{
		return $this->id;
	}
	
	private function getChildrenFromDB()
	{
		$childrenIDs = GirafGroup::getChildrenInGroup($this->id);
		foreach($children as $value)
		{
			$this->children[$value]= childProfile($value);
		}
	}	
	

	
}

?>