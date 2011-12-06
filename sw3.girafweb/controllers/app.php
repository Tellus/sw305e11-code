<?php

require_once(INCDIR . "controller.php");
require_once(INCDIR . "child.class.inc");
require_once(INCDIR . "device.class.inc");

/**
 * Group controller. Could be used for actual views, but right now its
 * focus is more on communicating with the views through JSON.
 **/
class App extends GirafController
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
		if ($selector == "device")
		{
			$dev = GirafDevice::getDevice($params["param1"]);;
			
			$apps = $dev->getApps($rType);
		}
		else
		{
			throw new Exception("Invalid selector type '$selector' requested.");
		}
		
		if ($rType == GirafRecord::RETURN_PRIMARYKEY) $output = json_encode($apps);
		elseif ($rType == GirafRecord::RETURN_RECORD)
		{
			$output = array();
			foreach ($apps as $app)
			{
				$output[] = array($app->id, $app->applicationName);
			}
		}
		
		echo json_encode($output);
	}
}

?>
