<?php

require_once(__DIR__ . "/user.class.inc");
require_once(__DIR__ . "/record.class.inc");
/**
* This class handles requist from the interface about the User 
*/
class userProfile
{
	private $id
	private $user;
	
	/**
	* \param Takes the userID to identify the user
	*/
	public function __construct($ID)
	{
		//get 
		$this->id = $ID;
		$this->user = GirafUser::getGirafUser($ID);		
	}
	//--------gets---------\\
	
	public function getUserId()
		{
			return $this->id;
		}
	
	/**
	* \return Returns the users username
	*/
	public function getUsername()
	{
		$temp = $this->user;
		return $temp->username;
		
	}
	
	/**
	* \return Returns the users mail
	*/
	public function getUserMail()
	{
		$temp = $this->user;
		return $temp->userMail;
	}
	
	/**
	* \return Returns the users fullname
	*/
	public function getFullName()
	{
		$temp = $this->user;
		return $temp->fullname;	
	}
	
	/**
	* this is an int what to do?
	* \return Returns the users role
	*/	
	public function getUserrole()
	{
		$temp = $this->user;
		return $temp->userRole;
	}

	/**
	* \return Returns the current Online status
	*/	
	public function getUserOnlineStatus()
	{
		$temp = $this->user;
		$result	= $temp->getOnlineStatus();	
		//handle userstatus
		return $result;
	}
	
	// ---------set--------\\

	/**
	* Change the users username
	* \param Takes the users Username 
	*/	
	public function setUsername($value)
	{
		$temp = $this->user;
		$temp->__set('username', $value);
	}
	
	/**
	* Change the users e-mail
	* \param Takes the e-mail 
	*/
	public function setUserMail($value)
	{
		$temp = $this->user;
		$temp->__set('userMail', $value);
	}

	/**
	* Change the users fullname
	* \param Takes the fullname 
	*/
	public function setFullName($value)
	{
		$temp = $this->user;
		$temp->__set('fullname', $value);
	}
	
	/** $value should be int to do????
	* Change the users role
	* \param Takes the userole  
	*/
	public function setUserrole($value)
	{
		$role = $this->identifyRole($value);
		$temp = $this->user;
		$temp->__set('userRole', $value);
	}

	/**
	* Changes the Online status of the user
	*/
	public function setUserOnlineStatus($statusvalue)
	{
		$bool = false;
		$status = $this->identifyStatus($statusvalue);
		if ($status != null)
		{
			$temp = $this->user;
			$bool = $temp->setOnlineStatus($status);
		}
		if($status==null ||$bool==false)
		{
			//error handling
		}
		//evt return ny status
	}
	
	
	//-------------others-----------------\\
	private function identifyRole($role)
	{
		$admin = 1;
		$moderator = 2;    
		$beparent = 3;
		$none= -1;
	
		if ($role == $admin)
		{
			return $admin;
		}
		elseif($role == $moderator)
		{
			return $moderator;
		}
		elseif($role == $beparent)
		{

			return $beparent;
		}
		else 
		{
			return none;
		}
	}
	
	private function identifyStatus($status)
	{
	    $statusOnline = 0;
		$statusOffline = 1;
		$statusAway = 2;
		$statusBusy = 3;
		$statusHidden = 4;
		
		if($status == $statusOnline)
		{
			return $statusOnline;
		}
		elseif($status == $statusOffline) 
		{
			return $statusOffline;
		}
		elseif($status == $statusAway) 
		{
			return $statusAway;
		}
		elseif($status == $statusBusy) 
		{
			return $statusBusy;
		}
		elseif($status == $statusHidden) 
		{
			return $statusHidden;
		}
		else
		{
			return null;
		}
	}
	
	//to handle
	public function identifyUserGroup()
	{
		$groupArray = $user->getUserGroups();
	}
	
	//to handle addToGroup($gId) from user class

	
	//----------save------------\\
	/**
	* 
	*/
	public function saveChanges()
	{
		$temp = $this->user;
		$temp->commit();
	}
	
}



?>