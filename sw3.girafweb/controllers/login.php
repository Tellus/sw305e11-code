<?php

require_once(INCDIR . "controller.php");

class Login extends GirafController
{
	public $session;
	
	function __construct()
	{
		parent::__construct();
		
		// Get session. Huarh!
		$this->session = GirafSession::getSession();
	}
	
	public function index()
	{
		$this->view("default/header");
		$this->view("default/login");
		$this->view("default/footer");
	}

	public function login($params = array())
	{
		// Step 1. Get userId from passed name.
		// Whee, we have a function for logging in now.
		$result = $this->session->loginUser($_POST["username"], $_POST["password"], true);
		
		if($result == true)
		{
			$isLoggedIn = true;
			// header("Location: index.php/main");
			// CallController("main");
			// Redirecting instead of calling a new controller is more
			// memory efficient and keeps the level of nesting manageable.
			Redirect("main");
		}
		else
		{
			$data = array();
			$data["error"] = "The username or password was incorrect.";
			$this->view("default/header");
			$this->view("default/login", $data);
			$this->view("default/footer");
		}
	}
	
	public function logout($params)
	{
		$this->session->close();
		Redirect(BaseUrl());
	}
	
	public function register($params)
	{
		$res = users::registerNewUser(	$_POST["username"],
										auth::hashString($_POST["password"]),
										$_POST["mail"]);
		if ($res == false || !isset($res))
		{
			die("Something went wrong during user creation!");
		}
		else
		{
			// We end registration simply by logging in. Later, an admin
			// might want e-mail authentication.
			$this->login($params);
		}
	}
	
	/**
	 * View for forgotten passwords.
	 * */
	public function forgot($params)
	{
		
	}
}

?>
