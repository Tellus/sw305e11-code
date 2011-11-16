<?php
require_once(__DIR__ . "/auth.func.inc"); 
require_once(__DIR__ . "/user.func.inc"); 
/**
* This class handles the login
*/
class login
{

	/**
	* Authenticate the username and password 
	* \param Takes a username
	* \param Takes a password
	* \return ????
	*/
	public static function doAuthentication($username, $password )
	{
		$IDstatus = false;
		if($username != "" && $password != "")
		{
			$IDstatus = auth::matchPassword($username, $password);
		}
		if($IDstatus==true)
		{
			//return
			return true;
		}
		else
		{
			//error => goto nybruger/glemt password
		}
	}
	
	/**
	* create a new user
	* \param Takes the new users username
	* \param Takes the new users password
	* \param Takes the new users e-mail
	* \param Takes the new users fullname
	* \param Takes the new users userrole	
	*/
	public static function createNewUser($username, $password, $email, $fullname = "", $userrole = -1)
	{
		$newUserStatus = user::registerNewUser($username, $password, $email, $fullname = "", $userrole = -1);
		if($newUserStatus==true)
		{
			return true;
		}
		else
		{
		//error
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