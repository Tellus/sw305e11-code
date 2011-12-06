<?php

require_once(INCDIR . "controller.php");
require_once(INCDIR . "child.class.inc");
require_once(INCDIR . "user.class.inc");

/**
 * Group controller. Could be used for actual views, but right now its
 * focus is more on communicating with the views through JSON.
 **/
class Group extends GirafController
{
	public function index()
	{
		throw new Exception ("This controller should NEVER be called directly.");
		/*
		// The default always defers to fallback.
		$params = array("controller" => "Main", "action" => "overview");
		$this->fallback($params["action"], $params);
		* */
	}
	
	public function fallback($action, $params = array())
	{
		if ($action == "list") $this->_list($params);
	}
	
	public function _list($params = array())
	{
		if (!array_key_exists("param0", $params)) throw new Exception("No user was requested.");
		
		$user = GirafUser::getGirafUser($params["param0"]); // Requested user.
		$rType = $params["param1"]; // Return type.
		$groups = $user->getGroups($rType);
		
		if ($rType == GirafRecord::RETURN_PRIMARYKEY) $output = json_encode($groups);
		elseif ($rType == GirafRecord::RETURN_RECORD)
		{
			$output = array();
			foreach ($groups as $group)
			{
				$output[] = array($group->id, $group->groupName);
			}
		}
		else
		{
			$output = json_encode("Invalid input data!");
		}
		
		echo json_encode($output);
	}
}

?>
