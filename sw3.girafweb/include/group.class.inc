<?php

/**
 * Data container class for group data.
 */
class GirafGroup extends GirafRecord
{
    function __construct()
    {
        parent::__construct(TABLE_GROUPS, "GirafGroup");
    }
    
    public static function getGirafGroup($gId)
    {
        return parent::getInstance($gId);
    }
}

?>
