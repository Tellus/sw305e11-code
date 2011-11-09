<?php

require_once(__DIR__ . "/record.class.inc");

/**
 * Data container class for group data.
 */
class GirafGroup extends GirafRecord
{
    public static function getSourceTable()
    {
        return TABLE_GROUPS;
    }
    
    public static function getPrimaryKey()
    {
        return "groupId";
    }
    
    public static function getGirafGroup($gId)
    {
        return parent::getInstance($gId);
    }
    
    public static function getGirafGroups($cond = null, $return_as = self::RETURN_PRIMARYKEY)
    {
        return parent::getRecords($cond, $return_as);
    }
}

?>
