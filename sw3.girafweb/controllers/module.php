<?php

require_once(INCDIR . "controller.php");

/**
 * The Module controller is intended to act as a sub-controller invoked
 * by any other controller, but primarily by the Main controller. It
 * acts as a middle layer between the primary Giraf controllers and the
 * controllers added later by uploaded applications. It's partially for
 * security reasons, making application controllers inaccessible without
 * accessing an authorised controller first. In that sense, this
 * controller is such an authority, but it will never output proper
 * headers and footers, almost always causing dysfunctional page loads.
 *  */
class Module extends GirafController
{
	public function index()
	{
		die("This controller should *never* be called directly!");
	}
	
	public function fallback($action, $params = array())
	{
		// Action should match exactly the application module we're
		// looking for.
		$action = strtolower($action);
		
		// Look for a fitting controller in the "apps" subfolder.
		// Unsorted, we aren't utilizing advanced searching algorithms.
		$mods = scandir(__DIR__ . "/apps", SCANDIR_SORT_NONE);
		
		foreach ($mods as $module)
		{
			// Ignore folders			
			if (is_dir(__DIR__ . "/apps/$module")) continue;
			
			if (strtolower($module) == $action)
			{
				// Invoke the module.
				// require_once(__DIR__ . "/apps/$module");
				
				// Remove the Module controller part.
				// Get a new non-associative path and remove the controller piece.
				$newPath = GetPath(false);
				array_shift($newPath);
				// Turn it associative.
				$newPath = GetAssocPath($newPath);
				
				_call_controller_internal($newPath["controller"], __DIR__ . "/apps/", $newPath["action"], $newPath);
				return;
			}
		}
		
		// If execution gets here, we have a serious problem.
		// I guess we should fall back to looking for the app and
		// present a default settings page for it. Later!
		throw new Exception("The submodule '$action' does not exist.");
	}
}

?>
