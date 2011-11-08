<?php

require_once(__DIR__ . "/sql_helper.inc");
require_once(__DIR__ . "/user.class.inc");

/**
 * The GirafRecord class is the base class for all data containers in the Giraf
 * web system. It contains automated facilities for retrieving field lists and
 * handling changes in them like record sets in ODBC. This unified inheritance
 * between all table-sourced container classes should simplify maintenance and
 * extension.
 */
abstract class GirafRecord
{
    /**
     * Name of the primary key in the underlying table. Used for calls to
     * createInstance().
     */
    protected static $primaryKey;
   
    /**
     * Contains the actual data from the database.
     */
    private $data;
    
    /**
     * Array of booleans that denote whether their corresponding data in the
     * $data array have been changed and thus need to be committed.
     */
    private $dirty_bits;
    
    /**
     * Name of the subclass that inherited this GirafRecord.
     */
    private static $subClass;
    
    /**
     * Name of the table that data comes from.
     */
    private static $sourceTable;
    
    /**
     * Sets all the dirty bits to a boolean value.
     * \param state New state, false/true.
     */
    protected function setDirtyState($state)
    {
        $len = count($this->dirty_bits);
        for($i = 0; $i < $len; $i++)
        {
            $this->dirty_bits[$i] = $state;
        }
    }
    
    /**
     * Creates a new GirafRecord instance, dynamically loading fields from the
     * database and registering the subclass for later instantitaion.
     * \param $table Name of the table that contains the underlying data.
     * \param $subclass Name of the subclass of GirafRecord that calls this constructor.
     */
    protected function __construct($table, $subclass)
    {
        // Set subclass data for later instantiations.
        self::$subClass = $subclass;
        self::$sourceTable = $table;
    
        /* We dynamically prep the internal data array with the exact
         * field names from the database itself.
         */
        $this->data = Array();
        $this->dirty_bits = Array();
        
        /**
         * The DESCRIBE statement is akin to a SELECT statement, only for table structure, not table data.
         */
        $result = sql_helper::selectQuery("DESCRIBE " . $table);
        
        if (!$result)
        {
            throw new Exception("The users table '" . $table . "' does not exist!");
        }
        
        while ($row = $result->fetch_assoc())
        {
            $this->data[$row["Field"]] = "";
            $this->dirty_bits[$row["Field"]] = false;
            
            if($row["Key"] == "PRI") self::$primaryKey = $row["Field"];
        }
        
        return;
    }
    
    /**
     * Gets the data associated with a particular field.
     * \param key Name of the field you want data from.
     * \return The value associated with that field. May be null.
     * \throws Exception If an invalid key is requested, an exception is thrown.
     */
    public function __get($key)
    {
        if (!array_key_exists($key, $this->data))
            throw new Exception("The requested key does not exist.");
        else
            return $this->datas[$key];
    }
    
    /**
     * Sets a value on the user. This will automatically also register that value to be committed to the database.
     * Note that only keys matching fields on the underlying table will be accepted.
     * \param key The key (field) to set a value to.
     * \param val The data to apply to the key.
     * \warning At this time, neither type nor writability is performed. As such, you may get erratic behaviour if you try to apply a string to the userId field (which is read-only - and an int).
     * \throws Exception Throws an exception if an invalid field is requested.
     */
    public function __set($key, $val)
    {
        // echo "Setting $key";
        if (!array_key_exists($key, $this->data))
            throw new Exception("The requested key does not exist.");
        else
        {
            $this->data[$key] = $val;
            $this->dirty_bits[$key] = true;
        } 
    }
    
    /**
     * Retrieves an instance of the object from the database, or an empty
     * object if not found.
     */
    protected static function getInstance($id)
    {
        // Retrieve on hit matching the user.
        $result = sql_helper::selectQuery("SELECT * FROM " . self::$sourceTable . " WHERE " . self::$primaryKey . "=$id");
        
        // If an error occurred or we got no hits, return false.
        if (!$result || $result->num_rows == 0) return false;
        
        // Retrieve the result.
        $row = $result->fetch_assoc();
        
        // Set the result into a new object.
        // $newU = new GirafUser();
        
        // Use reflection to get subclass info.
        // echo "Getting subclass " . self::$subClass;
        $refl = new ReflectionClass(self::$subClass);
        
        // Create a new subclass instance.
        $newU = $refl->newInstance();
        
        // Insert query data into the new instance.
        foreach ($row as $key=>$val)
        {
            $newU->$key = $val;
        }
        
        // Reset all the dirty bits for proper commit support.
        $newU->setDirtyState(false);
        
        // Return the new instance.
        return $newU;
    }
    
    /**
     * Commits changes made to the GirafUser back to the database. Handy for
     * changes like name, mail and the like when no further actions are needed.
     * \return true on success, false otherwise (check sql_helper::error).
     * \sa sql_helper::getErrMsg()
     * \todo Not written yet, but SqlUpdateCommand has been implemented to facilitate this.
     */
    public function commit()
    {
        
    }
}

?>
