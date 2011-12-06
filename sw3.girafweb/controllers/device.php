<?php

require_once(INCDIR . "controller.php");
require_once(INCDIR . "child.class.inc");
require_once(INCDIR . "device.class.inc");
require_once(INCDIR . "application.class.inc");

/**
 * Group controller. Could be used for actual views, but right now its
 * focus is more on communicating with the views through JSON.
 **/
class Device extends GirafController
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
		if (!array_key_exists("param0", $params)) throw new Exception("No child was requested.");
		
		$child = Girafchild::getGirafChild($params["param0"]);
		// $dev = GirafDevice::getDevice($params["param0"]);
		
		$rType = array_key_exists("param1", $params) ? $params["param1"] : GirafRecord::RETURN_PRIMARYKEY; // Return type.
		
		$devs = $child->getDevices($rType);
		
		if ($rType == GirafRecord::RETURN_PRIMARYKEY) $output = json_encode($devs);
		elseif ($rType == GirafRecord::RETURN_RECORD)
		{
			$output = array();
			foreach ($devs as $dev)
			{
				$output[] = array($dev->id, $dev->deviceIdent);
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
