<?php
require_once(__DIR__ . "/auth.func.inc"); 
require_once(__DIR__ . "/users.func.inc"); 
/**
* This class handles the login
*/
class login
{

	/**
	* Authenticate the username and password 
	* \param Takes a username
	* \param Takes a password
	* \return True if Authentication is approvied, else returns false
	*/
	public static function doAuthentication($username, $password, $doHash = true )
	{
		$IDstatus = false;
		if($username != "" && $password != "")
		{
			$IDstatus = auth::matchPassword($username, $password, $doHash);
		}
		echo "in DA $IDstatus";
		if($IDstatus==true)
		{
			//return
			return true;
		}
		else
		{
			//error
			return false;
		}
	}
	
	/**
	* create a new user
	* \param Takes the new users username
	* \param Takes the new users password
	* \param Takes the new users e-mail
	* \param Takes the new users fullname
	* \param Takes the new users userrole	
	* \return True if succesfull, and false otherwise 
	*/
	public static function createNewUser($username, $password, $email, $fullname = "", $userrole = -1)
	{
		$newUserStatus = users::registerNewUser($username, $password, $email, $fullname = "", $userrole = -1);
		if($newUserStatus==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	* handles when the user forget their password
	*/
	public static function forgottenPassword($username)
	{
		//todo
	}
}

?>