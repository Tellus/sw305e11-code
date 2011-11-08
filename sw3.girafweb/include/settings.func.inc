<?php

require_once(__DIR__ . "/sql_helper.inc");

/**
 * This class aids in the use of the global application settings for a single
 * GirafWeb installation. These settings cover all over things than the pure
 * basics in config.php that are required for the scripts to even run.
 */
abstract class GirafSettings
{
    /**
     * Retrieves a setting from the database. If the setting has no value, its
     * default is returned.
     * \param $name Name of the setting to retrieve. constants.inc has a nice selection.
     * \param $expand If set to true, the setting will have its variables expanded.
     * \return The value of the setting or null if the setting could not be found.
     */
    public static function getSetting($name, $expand = true)
    {
        $result = sql_helper::selectQuery("SELECT settingValue, settingDefault FROM " . TABLE_SETTINGS . " WHERE settingName='$name'");
        
        if ($result->num_rows == 0) return null;

        $row = $result->fetch_assoc();
        
        // Set value to settingValue or settingDefault if it is null.
        $value = $row["settingValue"] == null ? $row["settingDefault"] : $row["settingValue"];
        
        if ($expand) $value = self::expandVar($value);
        
        return $value;
    }
    
    /**
     * Expands variable setting values of the form ${var-name} to their full
     * counterparts.
     * \todo Neither designed nor implemented properly. Only "${ROOT}" is supported.
     * \param $input The string with variables to expand.
     * \return The input string with all expansions performed.
     */
    public static function expandVar($input)
    {
        return str_replace('${ROOT}', str_replace('/include', '', __DIR__), $input);
    }
}

?>
