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
		
		$data["userId"] = $currentUserData->id;
		
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
