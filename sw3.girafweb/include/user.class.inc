<?php

/**
 * Data container class for a user of the GIRAF system. This is typically
 * parents, kindergarten eachers and admins.
 */
class GirafUser
{
    public $id;
    public $username;
    public $fullname;
    public $userrole;
    public $usermail;
    public $lastaction;
    
    /**
     * Attempts to retrieve an existing user from the database and returns them
     * as a GirafUser instance.
     * /param uId the userId in the database corresponding to the user you want.
     * /return A new GirafUser object with the data from the database.
     */
    public static getGirafUser($uId)
    {
        // Retrieve on hit matching the user.
        $result = sql_helper::selectQuery("SELECT * FROM " . TABLE_USERS . " WHERE userId=$uId";
        
        // If an error occurred or we got no hits, return false.
        if (!$result || $result->num_rows() == 0) return false;
        
        // Retrieve the result.
        $row = $result->fetch_assoc();
        
        // Set the result into a new object.
        $newU = new GirafUser();
        
        $newU->$id = $row["userId"];
        $newU->$username = $row["username"];
        $newU->$fullname = $row["fullname"];
        $newU->$userrole = $row["userRole"];
        $newU->$usermail = $row["userMail"];
        $newU->$lastaction = $row["lastAction"];
        
        return $newU;
    }
    
    /**
     * Commits changes made to the GirafUser back to the database. Handy for
     * changes like name, mail and the like when no further actions are needed.
     * \return true on success, false otherwise (check sql_helper::error).
     * \sa {}sql_helper::getErrMsg()}
     */
    public function commit()
    {
        
    }
}

?>
