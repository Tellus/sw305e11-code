<?php

require_once("sql_helper.inc");
require_once("constants.inc");

/**
 * This file contains various authentication functions.
 */
class auth
{
    /**
     * Hashes a string to fit with our authentication scheme.
     * \todo{Actually write this function}
     */
    public static function hashString($input)
    {
        return $input;
    }

    /**
     * Retrieves the password of a given user.
     * \param userId The ID of the user you want a password for.
     * \return Returns the password as-is from the database (remember, hashed).
     */
    public static function getPassword($userId)
    {
        $result = sql_helper::selectQuery("SELECT password FROM " . TABLE_USERS . " WHERE userId=$userId");
        
        if ($row = $result->fetch_assoc())
        {
            return $row["password"];    
        }
        else
        {
            return null;
        }
    }
}
?>
