<?php

/**
 * Data container class for group data.
 */
public class GirafGroup
{
    /**
     * The group's name.
     */
    public $name;
    
    /**
     * The group's database identifier.
     */
    public $id;
    
    /**
     * User key to the superuser of the group.
     */
    public $adminId;
    
    function __constructor($gId = null, $gName = "", $gAdmin = null)
    {
        $name = $gName;
        $id = $gId;
        $adminId = $gAdmin;
    }
}

?>
