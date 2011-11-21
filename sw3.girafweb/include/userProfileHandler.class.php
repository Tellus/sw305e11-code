<?php

require_once(__DIR__ . "/user.class.inc");
require_once(__DIR__ . "/record.class.inc");
require_once(__DIR__ . "/childDevice.func.inc");
/**
* This class handles users info and functions
*/
class userProfile
{
	private $id;
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
	/**
	* Get the user id
	*\return The user's ID
	*/
	public function getUserId()
		{
			return $this->id;
		}
	
	/**
	* Get the username
	* \return Returns the user's username
	*/
	public function getUsername()
	{
		$temp = $this->user;
		return $temp->username;
		
	}
	
	/**
	* Get the users mail
	* \return Returns the user's mail
	*/
	public function getUserMail()
	{
		$temp = $this->user;
		return $temp->userMail;
	}
	
	/**
	* Get the users fullname
	* \return Returns the user's fullname
	*/
	public function getFullName()
	{
		$temp = $this->user;
		return $temp->fullname;	
	}
	
	/**
	* Get the user's role 
	* \return Returns the users role
	*/	
	public function getUserrole()
	{
		$temp = $this->user;
		return $temp->userRole;
	}

	/**
	* Get the users current online status
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
			return false;
		}
		return self::getUserOnlineStatus();
	}
	
	
	//-------------others-----------------\\
	/**
	* Is used to find users role, but not necessary, more to future work
	* \param A user role which is an integer
	* \return An valid integer corrosponding to a valid role
	*/
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
	
	/**
	* Is used to find the right online status, but not necessary, more to future work
	* \param An online status which is an integer
	* \return An valid integer corrosponding to a valid online status
	*/	
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
	
	/**
	*
	*/
	public function identifyUserGroup()
	{
		$temp = $this->user;
		return $temp->getUserGroups();
	}
	
	/**
	*
	*/
	public function addUserToGroup($gId)
	{
		$temp = $this->user;
		$temp->addToGroup($gId);
	}
	
	/**
	* Creates a child and connect it to the user
	* \param The child's name
	* \param The child's birthday as YYYY-MM-DD
	* \param An array of the child's abilities
	* \return True on succes and false otherwise;
	*/	
	public function addNewChildToUser($profileName, $profileBirthday, $abilities)
	{
		$childkey = ChildAndDevice::createChild($profileName, $profileBirthday, $abilities);
		if(!$childkey)
		{
			return "Error: This child were not registeret";
		}
		$connected = self::addChildToUser($childkey);
		if(!$connected) return false;
		return $connected;
	}

	/**
	* Find an existing child and connect it to the user
	* \param The child's name
	* \param The child's birthday as YYYY-MM-DD
	* \return True on succes and false otherwise;
	*/	
	public function addExistingChildToUser($profileName, $profileBirthday)
	{
		$childkey = ChildAndDevice::getChildId($profileName, $profileBirthday);
		if(!$childkey)
		{
			return false;
		}
		$connected = self::addChildToUser($childkey);
		if(!$connected) return false;
		return $connected;
	}
	/**
	* this is used by userProfile::addExistingChildToUser() and userProfile::addNewChildToUser()
	*\param a child id
	*\return True upon succes and false otherwise
	*/
	private function addChildToUser($childkey)
	{
		$connected = ChildAndDevice::connectChildAndUser($childkey, $this->id);
		if(!$connected)
		{
			return'Error: connection failed';
		}
		return true;
	}
	
	//----------save------------\\
	/**
	* Save all the changes in the database that have been made by the user
	* \return Returns true if succesful and false otherwise 
	*/
	public function saveChanges()
	{
		$temp = $this->user;
		$result=$temp->commit();
		if(!$result) return false;
		return $result;
	}
	
}



?>