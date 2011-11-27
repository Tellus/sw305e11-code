<?php

/**
 * The base controller class. Handles default request case and
 * attempts to determine possible actions.
 * */
abstract class GirafController
{
	/**
	 * We force subclasses to implement the index action, which
	 * is the basic of controller actions, the default.
	 * \param $params Single parameter (or array of paramaeters)
	 * sent to the action.
	 * \return Nothing. Ever.
	 * */
	public abstract function index();
	
	/**
	 * The fallback action is used when no valid action was found on the
	 * controller. Subclasses can override this to implement their own
	 * ways of handling unknown actions.
	 * \param $params Parameters.
	 * \return Nothing.
	 * */
	public function fallback($action, $params = array())
	{
		die("The action '$action' is unknown on the controller '" . get_class($this) . "'.");
	}
	
	function __construct()
	{
		// We'll probably need something here at some point.
	}
	
	/**
	 * Just a shorthand constructor.
	 * \return Returns a new instance of ther requested constructor
	 * class.
	 * */
	public static function getInstance()
	{
		return new GirafController();
	}
	
	/**
	 * Includes a php page into the current context. The page will have
	 * access to all parts of the current global scope.
	 * \param $page File to load.
	 * \param &$data Reference to an array of data that needs to be
	 * made available to the view. Each key will be the name of the
	 * variable made available, the value will be left as-is. After a
	 * view has run, the changed data is re-integrated with the array
	 * for later calls.
	 * \return Nothing. Everything is output.
	 * \note Recall that any loaded modules or scope pieces from other
	 * controllers or actions aren't available once the controller
	 * finishes an action.
	 * \throws Exception if the given file could not be found.
	 * */
	protected function view($page, $data = array())
	{
		// Append the ".php" file extension if omitted.
		if (substr($page, strlen($page)-4) != ".php") $page .= ".php";
		
		$path = BASEDIR . "views/$page";
		
		if (!file_exists($path)) throw new Exception("The file '$path' was not found");
		else
		{
			// I've taken a queue from CodeIgniter here. Variables are
			// expanded into variable variables before including the
			// target file.
			
			// The built-in extract() function is wonderful in this 
			// respect.
			// I considered reference extraction, but this would allow
			// the changes from one view to bleed into another. This
			// violates the "non-action" requirement for the view.
			extract($data);
						
			require($path);
		}
	}
}

?>
