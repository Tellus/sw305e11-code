<?php

require_once(__DIR__ . "/sql_helper.inc");
require_once(__DIR__ . "/constants.inc");
require_once(__DIR__ . "/user.class.inc");

/**
 * This file contains various authentication functions.
 */
class auth
{
    /**
     * Hashes a string to fit with our authentication scheme. The function strips
     * out the hashing parameters typically prepended to the output. This is a
     * possible (albeit unlikely) security hole.
     * \param input The string to hash.
     * \return The string in hashed form.
     */
    public static function hashString($input)
    {
        $hash = crypt($input, '$6$rounds=' . crypt_rounds . '$' . crypt_salt . '$');
	    $hash = substr($hash, strlen('$6$rounds=' . crypt_rounds . '$'));
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
    
    /**
     * Checks to see if a given user/pass combination matches that of current
     * data.
     * \param userId Database ID of the user to check against.
     * \param pass The password you want to check.
     * \param doHash (Optional) Set to true if you have not hashed the password yourself.
     * \return true if the passwords match, false otherwise or if the user was not found.
     */
    public static function matchPassword($userId, $pass, $doHash = true)
    {
        if (is_numeric($userId))
        {
            $cond = GirafUser::getPrimaryKey() . "=$userId";
        }
        else
        {
            $cond = "username='$userId'";
        }
        $sql = "SELECT password FROM " . TABLE_USERS . " WHERE $cond";
    
        $result = sql_helper::selectQuery($sql);    

        // echo $sql;

        if (!$result) throw new Exception(sql_helper::getErrMsg());
    
        if ($result->num_rows == 0) return false;
    
        $row = $result->fetch_assoc();
        if ($doHash) $pass = auth::hashString($pass);
            
        $matches = ($pass == $row["password"]);
            
        // echo $pass . "<br/> versus <br/>" . $row["password"] . "<br/>Match : " . $matches;
            
        return $pass == $row["password"] ? true : false;
    }
}
?>
