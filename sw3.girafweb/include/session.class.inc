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
	
	public function __get($key)
	{
		if (!array_key_exists($key, $this->session_data)) return null;
		else return $this->session_data[$key];
	}
	
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
		if (isset(self::$_instance[$name])) return self::$_instance[$name];
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
    
    public static function getSessionId()
    {
        return session_id();
    }
    
    /**
     * Closes the currently active sesion.
     * \sa session_close()
     */ 
    public static function close()
    {
        session_destroy();
    }
    
    /**
     * Returns the database ID of the user currently logged into this session.
     * \return The databse ID if the user is logged in, false otherwise.
     */
    public static function getCurrentUser()
    {
        $v = self::get("userId");

        return isset($v) ? $v : null;
    }
}

?>
