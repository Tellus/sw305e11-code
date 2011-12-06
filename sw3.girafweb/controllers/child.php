<?php

require_once(INCDIR . "controller.php");
require_once(INCDIR . "child.class.inc");
require_once(INCDIR . "group.class.inc");

/**
 * Group controller. Could be used for actual views, but right now its
 * focus is more on communicating with the views through JSON.
 **/
class Child extends GirafController
{
	public function index()
	{
		throw new Exception ("This controller should NEVER be called directly.");
	}
	
	public function fallback($action, $params = array())
	{
		if ($action == "list") $this->_list($params);
	}
	
	public function _list($params = array())
	{
		// var_dump($params);
		
		if (!array_key_exists("param0", $params)) throw new Exception("No selector was requested.");
		if (!array_key_exists("param1", $params)) throw new Exception("No selector id was requested.");
		
		if (!array_key_exists("param2", $params)) $rType = GirafRecord::RETURN_PRIMARYKEY;
		else $rType = $params["param2"];
		
		$selector = $params["param0"];
		if ($selector == "group")
		{
			$group = GirafGroup::getGirafGroup($params["param1"]);
			
			$kids = $group->getChildren($rType);
		}
		elseif ($selector == "user")
		{
			$user = GirafUser::getGirafUser($params["param1"]); // Requested user.
			
			$kids = $user->getChildren($rType);
		}
		else
		{
			throw new Exception("Invalid selector type '$selector' requested.");
		}
		
		// var_dump($kids);
		
		if ($rType == GirafRecord::RETURN_PRIMARYKEY) $output = json_encode($kids);
		elseif ($rType == GirafRecord::RETURN_RECORD)
		{
			$output = array();
			foreach ($kids as $kid)
			{
				$output[] = array($kid->id, $kid->profileName);
			}
		}
		
		echo json_encode($output);
	}
}

?>
