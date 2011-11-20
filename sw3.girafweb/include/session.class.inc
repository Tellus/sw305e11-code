<?php

/**
 * Simple class to aid in the maintenance of PHP sessions. This is particularly useful (not to mention necessary) when working with login functionality such as ours.
 * Note that this class is very basic, only slightly better than syntactic sugar as it simply wraps around existing session functions.
 * Note the class contains only static functions and it should be considered static itself.
 */
class GirafSession
{
	private $session_data;
	
	public function __get($key)
	{
		if (!array_key_exists($key, $_SESSION)) return null;
		else return $_SESSION[$key];
	}
	
	public function __set($key, $val)
	{
		$_SESSION[$key] = $val;
	}
	
	/**
	 * Creates a new GirafSession instance.
	 * \param $sid Optionally the ID to give the retrieved session.
	 * */
	function __construct($sid = null)
	{
		if (session_id() != "")
		{
			session_id($sid);
		}
		else
		{
			session_start();
		}
	}
	
    public static function get($name)
    {
        return $_SESSION[$name];
    }
    
    public static function set($name, $val)
    {
        $_SESSION[$name] = $val;
    }
    
    public static function getSessionId()
    {
        return session_id();
    }
    
    /**
     * Retrieves a session's data. If no session exists, a new session
     * is created.
     * \return The session id (SID). Always.
     * \note The method is mostly a way of circumventing PHP's E_NOTICE
     * message when session_start is called several times over.
     */
    public static function getSession($id = -1)
    {
		$gs = new GirafSession();
		
		if (session_id() == "")	session_start();
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
