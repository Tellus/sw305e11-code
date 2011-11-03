<?php

/* This file contains functions that have specialised data retrieval
 * purposes.
 */

require_once("sql_helper.inc");

/**
 * 
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
function registerNewUser($username, $password, $email, $fullname = "", $userrole = -1)
{
    $sql = "INSERT INTO " . TABLE_USERS . " (username, password, userMail";
    
    if ($fullname != "") $sql .= ", fullname";
    if ($userrole > -1) $sql .= ", userRole";

    $sql .= ") VALUES ('$username', '$password', '$email'";
    
    if ($fullname != "") $sql .= ", '$fullname'";
    if ($userrole > -1) $sql .= ", $userrole";
    
    $sql .= ")";
    
    $result = sql_helper::insertQuery($sql);
    
    if (!$result)
    {
        return sql_helper::getErrMsg();
    }
}
?>
