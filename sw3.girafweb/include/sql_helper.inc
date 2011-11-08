<?php

require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/constants.inc");

/**
 * A base helper class for running basic SQL queries with no
 * specialisation or fluff. Will self-instantiate with data
 * found in config.php.
 * \todo Use bind_params and the like to make more secure SQL transactions.
 */
class sql_helper
{
    /**
     * MySQLi instance of the active connection to the MySQL database.
     * We attempt to make this persistent.
     */
    protected static $connection;
    
    public function getConnection()
    {
        return self::$connection;
    }
    
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
    
    /**
     * Proxy call to real_escape_string for the internal connection object.
     * \param $in The string to escape.
     * \return An escaped string proper to the current connection.
     */
    public static function escapeString($in)
    {
        return self::$connection->real_escape_string($in);
    }
    
    /**
     * Proxy call for $connection->prepare().
     * \param $query The query to prepare.
     */
    public static function prepare($query)
    {
        return self::$connection->prepare($query);
    }
    
    /**
     * Performs a single query on the database and returns the first part of the first result.
     * \param $query The query to perform.
     * \return The first result.
     */
    public static function simpleQuery($query)
    {
        $row = self::$connection->query($query)->fetch_assoc();
        return $row[0];
    }
}
sql_helper::staticConstructor();

?>
