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
 * \param
 * \param
 * \param
 * \param
 * \return Returns FALSE on error, new user id from the database on success.
 */
function registerNewUser($username, $password, $email, $fullname = "", $userrole = -1)
{
    $sql = "INSERT INTO 
}

?>
