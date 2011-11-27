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

		// Alright, time constraint.
		// We're gonna bruteforce the fuck out of this thing.

		// Get all children for this user's groups.
		// Even more bruteforce. Get *all* children.
		$kids = GirafChild::getGirafChildren(null, GirafChild::RETURN_RECORD);
		// Pop them into the view data array.
		$data["kids"] = $kids;

		$kidsApps = array(); // Array with children and their apps.
		// Get all apps on all devices for all the children.
		foreach($kids as $child)
		{
			// Get devices.
			$devs = GirafDevice::getDevices("ownerId=" . $child->id, GirafRecord::RETURN_RECORD);
			$childApps = array(); // Key = appId.
			// Get apps for each device.
			
			// For each device, get the apps.
			foreach ($devs as $device)
			{
				$apps = GirafDevice::getAppsOnDevice($device->id);
				// For each app id, retrieve app data and give to the top childApps array.
				
				foreach ($apps as $appId)
				{
					$appData = GirafApplication::getApplication($appId);
					// Only add the app if it is not null and it hasn't already been added.
					if ($appData != null)
					{
						if (!array_key_exists($appId, $childApps)) $childApps[$appId] = $appData;
					}
				}
			}
			
			// At this point, we should have all unique apps for a cihld in
			// childApps.
			$kidsApps[$child->id] = $childApps;
		}
		
		// Toss kidsApps into the data array as well.
		$data["kidsApps"] = $kidsApps;
		
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
