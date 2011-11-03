<?php

require_once("config.php");
require_once("constants.inc");

/**
 * A base helper class for running basic SQL queries with no
 * specialisation or fluff. Will self-instantiate with data
 * found in config.php.
 */
class sql_helper
{
    /**
     * MySQLi instance of the active connection to the MySQL database.
     * We attempt to make this persistent.
     */
    protected static $connection;
    
    private static $lastError;
    
    public function getErrMsg()
    {
        self::$lastError = self::$connection->error;
        return self::$lastError;
    }
    
    /**
     * Static "constructor" that preps the connection for consequent usage.
     */
    public static function staticConstructor()
    {
        // Note the "p:" prefix. We request a persistent connection for ease of use.
        self::$connection = new mysqli("p:" . db_host, db_user, db_pass, db_db, db_port);
        
        if (!self::$connection)
        {
            self::$lastError = self::$connection->error();
        }
    }
    
    /**
     * A simple safeguard method that is called almost everywhere within the class, ensuring safe
     * execution.
     */
    private static function _checkInit()
    {
        if (!self::$connection)
        {
            die("sql_helper is not properly initialised!");
        }    
    }
    
    /**
     * Performs a broad query and returns the result blatantly. Specialised functions
     * may desire to work this a bit more.
     * \param sql The complete SQL string to run.
     * \return Anything that it feels like returning. (That is, mixed.)
     */
    private static function _baseQuery($sql)
    {
        self::_checkInit();
        
//        $sql = self::$connection->real_escape_string($sql);
        
        return self::$connection->query($sql);   
    }
    
    /**
     * Performs a simple selection query from the database.
     * \param sql The complete SQL query (including "select"). It will be automatically escaped!
     * \return Returns a MySQLi_Result object that I hope you know how to work with :-)
     */
    public static function selectQuery($sql)
    {
        return self::_baseQuery($sql);
    }
    
    /**
     * Performs an insert query on the database.
     * \param sql The <b>complete</b> query (including the INSERT statement). Will be escaped.
     * \return Returns TRUE if succesful, FALSE otherwise (in which case, check the error message).
     */
    public static function insertQuery($sql)
    {
        return self::_baseQuery($sql);
    }
}
sql_helper::staticConstructor();

?>
