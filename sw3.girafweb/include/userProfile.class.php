<?php

require_once(__DIR__ . "/user.class.inc");
require_once(__DIR__ . "/record.class.inc");
/**
* This class handles requist from the interface about the User 
*/
class userProfile
{
    /**
     * The user is currently online and visible.
     */
    const STATUS_ONLINE = 0;
    
    /**
     * The user is offline.
     */
    const STATUS_OFFLINE = 1;
    
    /**
     * The user is away.
     */
    const STATUS_AWAY = 2;
    
    /**
     * The user is busy.
     */
    const STATUS_BUSY = 3;
    
    /**
     * The user is online but invisible.
     */
    const STATUS_HIDDEN = 4;
	
	const ADMIN = 1;
	const MODERATOR = 2;
	const BEPARENT = 3;
	const NONE = -1;
	
	private $user;
	
	/**
	* \param Takes the userID to identify the user
	*/
	public function __construct($ID)
	{
		//get 
		$this->user = GirafUser::getGirafUser($ID);
	}
	//--------gets---------\\
	
	/**
	* \return Returns the users username
	*/
	public function getUsername()
	{
		return $user->username;
	}
	
	/**
	* \return Returns the users mail
	*/
	public function getUserMail()
	{
		return $user->userMail;
	}
	
	/**
	* \return Returns the users fullname
	*/
	public function getFullName()
	{
		return $user->fullname;
	}
	
	/**
	* this is an int what to do?
	* \return Returns the users role
	*/	
	public function getUserrole()
	{
		return $user->userRole;
	}

	/**
	* \return Returns the current Online status
	*/	
	public function getUserOnlineStatus()
	{
		$result	= $user->getOnlineStatus();	
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
		$user->__set('username', $value);
	}
	
	/**
	* Change the users e-mail
	* \param Takes the e-mail 
	*/
	public function setUserMail($value)
	{
		$user->__set('userMail', $value);
	}

	/**
	* Change the users fullname
	* \param Takes the fullname 
	*/
	public function setFullName($value)
	{
		$user->__set('fullname', $value);
	}
	
	/** $value should be int to do????
	* Change the users role
	* \param Takes the userole  
	*/
	public function setUserrole($value)
	{
		$user->__set('userRole', $value);
	}

	/**
	* Changes the Online status of the user
	*/
	public function setUserOnlineStatus($statusvalue)
	{
		$bool = false;
		$status = identifyStatus($statusvalue);
		if ($status != null)
		{
			$bool = $user->setOnlineStatus($status);
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
		if ($role == ADMIN)
		{
		}
		elseif($role == MODERATOR)
		{
		}
		elseif($role == BEPARENT)
		{
		}
		else 
		{
		}
	}
	
	private function identifyStatus($status)
	{
		if($status == STATUS_ONLINE)
		{
			return STATUS_ONLINE;
		}
		elseif($status == STATUS_OFFLINE) 
		{
			return STATUS_OFFLINE;
		}
		elseif($status == STATUS_AWAY) 
		{
			return STATUS_AWAY;
		}
		elseif($status == STATUS_BUSY) 
		{
			return STATUS_BUSY;
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
		$user->commit();
	}
	
}



?>