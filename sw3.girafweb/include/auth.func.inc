<?php

require_once("sql_helper.inc");
require_once("constants.inc");

/**
 * This file contains various authentication functions.
 */
class auth
{
    /**
     * Hashes a string to fit with our authentication scheme. The function strips
     * out the hashing parameters typically output.
     * \param input The string to hash.
     */
    public static function hashString($input)
    {
        $hash = crypt($input, '$6$rounds=5000$' . crypt_salt . '$');
	    $hash = substr($hash, strlen('$6$rounds=5000$'));
	    $hash = substr($hash, 0, strlen($hash)-1);
	
	    // Current hash seems to end up at about 102 characters.
	    // echo('Key length: ' . strlen($hash));
	
	    return $hash;
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
