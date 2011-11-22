<?php

/**
 * Simple class to aid in the maintenance of PHP sessions. This is particularly useful (not to mention necessary) when working with login functionality such as ours.
 * Note that this class is very basic, only slightly better than syntactic sugar as it simply wraps around existing session functions.
 * Note the class contains only static functions and it should be considered static itself.
 */
class GirafSession
{
	private $session_data;
	
	private static $_instance;
	
	/**
	 * Get a session variable.
	 * \param $key Name of the variable.
	 * \return Value of the key, or null if not found.
	 * */
	public function __get($key)
	{
		if (!array_key_exists($key, $this->session_data)) return null;
		else return $this->session_data[$key];
	}
	
	/**
	 * Sets a session variable.
	 * \param $key Name of the variable.
	 * \param $val New value for the variable.
	 * \return Null. Always.
	 * */
	public function __set($key, $val)
	{
		$this->session_data[$key] = $val;
	}
	
	/**
	 * Creates a new GirafSession instance.
	 * \param $sid Optionally the ID to give the retrieved session.
	 * */
	private function __construct()
	{
		if (session_id() == "")
		{
			session_start();
		}
		
		$this->session_data = &$_SESSION;
	}
	
	/**
	 * Retrieves the current session, or a new session if none are
	 * present.
	 * \return GirafSession singleton object.
	 * */
	public static function getSession()
	{
		if (!isset(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Disables cloning of singleton.
	 * */
	private function __clone(){}
	
	/**
     * Get a session value.
     * \param $name Key of the value.
     * \return Value of tkey or null if not found.
     * \deprecated The use of static session functions is now
     * deprecated. Retrieve the session with getSession and modify it
     * directly.
     * */
    public static function get($name)
    {
		if (isset(self::$_instance->$name)) return self::$_instance->$name;
		else return null;
    }
    
    /**
     * Sets a session value.
     * \param $name Key for the value.
     * \param $val Value for the key.
     * \return Null.
     * \deprecated The use of static session functions is now
     * deprecated. Retrieve the session with getSession and modify it
     * directly.
     * */
    public static function set($name, $val)
    {
		self::$_instance->$name = $val;
    }
    
    /**
     * Returns the session's identifier string.
     * \return String, the session id.
     * \note Basically a proxy call to session_id().
     * */
    public static function getSessionId()
    {
        return session_id();
    }
    
    /**
     * Closes the currently active sesion.
     * \sa session_close()
     */ 
    public function close()
    {
        session_destroy();
    }
    
    /**
     * Returns the database ID of the user currently logged into this session.
     * \return The databse ID if the user is logged in, false otherwise.
     */
    public function getCurrentUser()
    {
        return $this->userId;
    }
    
    /**
     * Performs the motions required for logging in a user.
     * \param $user ID or name of the user to log in.
     * \param $password The user's password.
     * \param $hash If true, the password will be hashed. Set this to
     * false if you did this before calling the function.
     * \return True if login succeeded, false otherwise. See
     * GirafSession::errMsg to see why.
     * */
    public function loginUser($user, $password, $hash = false)
    {
		// Make sure user is an id.
		if (is_numeric($user))
		{
			// Good.
		}
		elseif (is_string($user))
		{
			$user = users::getUserId($user);
		}
		
		// var_dump($user, $password);
		
		// Finally, check the password.
		if (auth::matchPassword($user, $password, $hash))
		{
			$this->errMsg = null;
			$this->userId = $user;
			return true;
		}
		else
		{
			$this->errMsg = "The username/password combination did not match.";
			$this->userId = null;
			return false;
		}
	}
}

?>
