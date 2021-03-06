<?php

/* This file contains functions that have specialised data retrieval
 * purposes.
 */

require_once("sql_helper.inc");
require_once("user.class.inc");

class users
{

    /**
     * Retrieves the user currently logged in through the active session.
     */
    function getCurrentUser()
    {

    }

    /**
     * Creates a new user in the database.
     * \param username The username (the identifier they log in with).
     * \param password The password of the user. If hash watermarks are not found, it is rehashed.
     * \param email The email address of the user.
     * \param fullname (Optional) full name of the user. Can be supplied later.
     * \param userrole (Optional) optional role type of the user (admin, moderator, parent, none).
     * \return Returns FALSE on error, new user id from the database on success.
     */
    public static function registerNewUser($username, $password, $email, $fullname = "", $userrole = -1)
    {
        $sql = "INSERT INTO " . TABLE_USERS . " (username, password, userMail";
        
        $fullname = sql_helper::escapeString($fullname);
        if ($fullname != "") $sql .= ", fullname";
        if ($userrole > -1) $sql .= ", userRole";

		$username = sql_helper::escapeString($username);
		$email = sql_helper::escapeString($email);
        $sql .= ") VALUES ('$username', '$password', '$email'";
        
        if ($fullname != "") $sql .= ", '$fullname'";
        if ($userrole > -1) $sql .= ", $userrole";
        
        $sql .= ")";
        
        $result = sql_helper::insertQuery($sql);
        
        if (!$result)
        {
            return sql_helper::getErrMsg();
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Changes the password of a user.
     * \param $userId Id of the user to modify.
     * \param $newPass New password, either hashed or clear.
     * \param $doCrypt If true, $newPass will be hashed. If false, it will be used as-is.
     * \return True on success, false otherwise.
     */
    public static function changePassword($userId, $newPass, $doCrypt = true)
    {
        if ($doCrypt) $newPass = auth::hashString($newPass);
    
        sql_helper::updateQuery("UPDATE " . TABLE_USERS . " SET password='$newPass' WHERE userId=$userId");
        
        if (sql_helper::getLastError()) return false;
        else return true;
    }
	
	/*
	* Uses the username to find the id of the user.
	* \param Takes the username of the user as input
	* \return Returns the id of the user with the inputted username
	*/
	public static function getUserId($username)
	{
		$username = sql_helper::escapeString($username);
		$result = sql_helper::selectQuery("SELECT " . GirafUser::getPrimaryKey() . " FROM " . GirafUser::getSourceTable() . " WHERE username='$username'");
		
		if (!$result) return false;
		
		// We need to actually return the id of the result, not the resultset... sigh.
		
		$row = $result->fetch_assoc();
		
		return $row[GirafUser::getPrimaryKey()];
	
	}
	
	/**
	 * Get groups that this user is bound to.
	 * */
	public function getGroups($returnAs = GirafRecord::RETURN_PRIMARYKEY)
	{
		$g = sql_helper::selectQuery("SELECT groupKey FROM " . TABLE_USER_GROUP_KEYS . " WHERE userKey=" . $this->id);
		
		$ret = array();
		if ($g === false) return $ret;
		
		if ($returnAs == GirafRecord::RETURN_PRIMARYKEY)
		{
			while ($row = $g->fetch_row()) $ret[] = $row[0];
		}
		elseif ($returnAs == GirafRecord::RETURN_RECORD)
		{
			while ($row = $g->fetch_row()) $ret[] = GirafGroup::getGirafGroup($row[0]);
		}
		else
		{
			throw new Exception ("Invalid return type $returnAs requested.");
		}
		
		return $ret;
	}
}
?>
