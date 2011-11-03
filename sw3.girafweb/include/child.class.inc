<?php

require_once("sql_helper.inc");
require_once("constants.inc");

/**
 * Data container class for a child in the system.
 */
public class GirafChild
{
    public $id;
    public $name;
    public $birthday;
    public $updateTimestamp;

    /**
     * Gets the first device associated with the child.
     * \return A GirafDevice associated with the child, or null.
     * \todo{Write the function}
     */
    public function getDevice()
    {
        return null;
    }
    
    function __constructor()
    {
    
    }
    
    /**
     * Creates a new GirafChild instance with the data found under the given
     * id.
     * \param childId ID of the child we're retrieving.
     */
    public static function getChild($childId)
    {
        $result = sql_helper::selectQuery("SELECT * FROM " . TABLE_CHILDREN . " WHERE profileId=" . $childId);
        
        if (!result) return null;
        
        $row = $result->fetch_assoc();
        $ret = new GirafChild();
        $ret->$id = $row["profileId"];
        $ret->$name = $row["profileName"];
        $ret->$birthday = $row["age"];
        $ret->$updateTimestamp = $row["lastUpdated"];
        
        return $ret;
    }
}

?>
