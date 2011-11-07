<?php

require_once(__DIR__ . "/sql_helper.inc");

/**
 * Data container class for a user of the GIRAF system. This is typically
 * parents, kindergarten eachers and admins.
 */
class GirafUser
{   
    /**
     * Contains the actual data from the database.
     */
    private $data;
    
    /**
     * Array of booleans that denote whether their corresponding data in the $data array have been changed and thus need to be committed.
     */
    private $dirty_bits;
    
    function __constructor()
    {
        /* We dynamically prep the internal data array with the exact
         * field names from the database itself.
         */
        $data = Array();
        
        /**
         * The DESCRIBE statement is akin to a SELECT statement, only for table structure, not table data.
         */
        $result = sql_helper::selectQuery("DESCRIBE " . TABLE_USERS);
        
        if (!$result)
        {
            throw new Exception("The users table '" . TABLE_USERS . "' does not exist!");
        }
        
        while ($row = $result->fetch_assoc())
        {
            $data[$row["Field"]] = "";
        }
        
        return;
    }
    
    /**
     * Gets the data associated with a particular field.
     * \param key Name of the field you want data from.
     * \return The value associated with that field. May be null.
     * \throws Exception If an invalid key is requested, an exception is thrown.
     */
    public function __get($key)
    {
        if (!array_key_exists($key, $data))
            throw new Exception("The requested key does not exist.");
        else
            return $data[$key];
    }
    
    /**
     * Sets a value on the user. This will automatically also register that value to be committed to the database.
     * Note that only keys matching fields on the underlying table will be accepted.
     * \param key The key (field) to set a value to.
     * \param val The data to apply to the key.
     * \warning At this time, neither type nor writability is performed. As such, you may get erratic behaviour if you try to apply a string to the userId field (which is read-only - and an int).
     * \throws Exception Throws an exception if an invalid field is requested.
     */
    public function __set($key, $val)
    {
        if (!array_key_exists($key))
            throw new Exception("The requested key does not exist.");
        else
        {
            $data[$key] = $val;
            $dirty_bits[$key] = true;
        } 
    }
    
    /**
     * Attempts to retrieve an existing user from the database and returns them
     * as a GirafUser instance.
     * /param uId the userId in the database corresponding to the user you want.
     * /return A new GirafUser object with the data from the database.
     */
    public static function getGirafUser($uId)
    {
        // Retrieve on hit matching the user.
        $result = sql_helper::selectQuery("SELECT * FROM " . TABLE_USERS . " WHERE userId=$uId");
        
        // If an error occurred or we got no hits, return false.
        if (!$result || $result->num_rows == 0) return false;
        
        // Retrieve the result.
        $row = $result->fetch_assoc();
        
        // Set the result into a new object.
        $newU = new GirafUser();
        
        $newU->id = $row["userId"];
        $newU->username = $row["username"];
        $newU->fullname = $row["fullname"];
        $newU->userrole = $row["userRole"];
        $newU->usermail = $row["userMail"];
        $newU->lastaction = $row["lastAction"];
        
        return $newU;
    }
    
    /**
     * Commits changes made to the GirafUser back to the database. Handy for
     * changes like name, mail and the like when no further actions are needed.
     * \return true on success, false otherwise (check sql_helper::error).
     * \sa sql_helper::getErrMsg()
     * \todo Not written yet, but SqlUpdateCommand has been implemented to facilitate this.
     */
    public function commit()
    {
        
    }
}

?>
