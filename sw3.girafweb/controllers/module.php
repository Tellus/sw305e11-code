<?php

require_once(INCDIR . "controller.php");
require_once(INCDIR . "application.class.inc");

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
		// looking for, either in lowercase string OR appId.
		if (is_numeric($action))
		{
			// Identify based on app id.
			$app = GirafApplication::getApplication($action);
			if ($app === null || $app === false) throw new Exception("AppId $action does not exist. At all.");
			
			$action = $app->applicationSystemName;
		}
		elseif (is_string($action))
		{
			// Take it on face value.
		}
		else
		{
			throw new Exception("Invalid app identifier passed, '" . get_class($action) . "'");
		}
		
		$action = strtolower($action);
		
		// Look for a fitting controller in the "apps" subfolder.
		// Unsorted, we aren't utilizing advanced searching algorithms.
		$mods = scandir(__DIR__ . "/apps");
		
		foreach ($mods as $module)
		{
			// Ignore folders			
			if (is_dir(__DIR__ . "/apps/$module")) continue;
			
			if (strtolower($module) == "$action.php")
			{
				// Invoke the module.
				// require_once(__DIR__ . "/apps/$module");
				
				// Remove the Module controller part.
				// Get a new non-associative path and remove the controller piece.
				// Turn it associative.
				
				$path = implode('/', $params);
				// Remove the first part of the path, the original controller.
				$path = substr($path, strpos($path, '/'));
				// var_dump($path);
				$newPath = GetAssocPath($path);
				
				// _call_controller_internal($params["action"], __DIR__ . "/apps/", $params["param0"], $params);
				$newPath["controller"] = $action; // Slight override. We want string, not id.
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
