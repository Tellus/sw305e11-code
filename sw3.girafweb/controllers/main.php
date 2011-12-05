<?php

require_once(INCDIR . "controller.php");
require_once(INCDIR . "child.class.inc");
require_once(INCDIR . "device.class.inc");

class Main extends GirafController
{
	public function index()
	{
		// The default always defers to fallback.
		$params = array("controller" => "Main", "action" => "overview");
		$this->fallback($params["action"], $params);
	}
	
	/**
	 * Main slightly mis-uses the intentions of the fallback function.
	 * It uses the fallback to identity single modules requested in an
	 * out-of-order way and load them as any other requested module.
	 * */
	public function fallback($action, $params = array())
	{
		// Prepare data array.
		$data = array();
		
		// Since it's a feature used numerous times throughout this script,
		// let's just get the current user.

		$s = GirafSession::getSession();

		$currentUserData = GirafUser::getGirafUser($s->getCurrentUser());

		// In order to make the data more digestible to the view, we'll
		// order the necessary data in the following nesting:
		// Groups -> Children -> Apps. Although apps and children are
		// currently just mass-read into arrays, the implementation may
		// make AJAX/JSON loading later more easily supported. So
		// without further ado:
		
		// 1. Get groups.
		$groups = $currentUserData->getGroups(GirafRecord::RETURN_RECORD);
		
		// 2. Get children for each group.
		$children = array();
		
		foreach($groups as $group)
		{
			$children[$group->id] = $group->getChildren(GirafRecord::RETURN_RECORD);
		}
		
		// 3. Get apps for each child.
		// 3a. Get devices for each child.
		// 3b. Get apps for each device.
		$apps = array(); // Key: childId, value: array of apps.
		foreach ($children as $child)
		{
			$apps[$child->id] = array(); // Initialize.
			
			// We try to reduce the overhead of double-entry apps, so we
			// start by getting unique app ID's THEN getting records.
			
			foreach($child as $subarr)
			{
				
			}
			
			if (is_array($child))
			{
				foreach ($child as $child_arr)
				{
					$devices = $child_arr->getDevices();
					
					$dev_apps = array();
					
					foreach ($devices as $dev)
					{
						$dev_apps_temp = $dev->getApps();
						$dev_apps = array_merge($dev_apps_temp, $dev_apps);
					}
				}
			}
			else
			{
				$devices = $child->getDevices();
				
				
				
				foreach ($devices as $dev)
				{
					$dev_apps_temp = $dev->getApps();
					$dev_apps = array_merge($dev_apps_temp, $dev_apps);
				}
			}
		}

		// Get all children for this user's groups.
		// Even more bruteforce. Get *all* children.
		// $kids = GirafChild::getGirafChildren(null, GirafChild::RETURN_RECORD);
		// Pop them into the view data array.
		$data["kids"] = $children;
		
		// Toss kidsApps into the data array as well.
		$data["apps"] = $apps;
		
		// Invoke the views.
		$this->view("default/header", $data);
		$this->view("default/main_stub", $data);
		// The view should be capable of calling the required sub modules.
		// We need a standardisation in javascript that makes sure it
		// knows how to replace the module section. No php there.
		$this->view("default/footer", $data);
	}
}

?>
