<?php

require_once(__DIR__ . "/sql_helper.inc");
require_once(__DIR__ . "/user.class.inc");

/**
 * The GirafRecord class is the base class for all data containers in the Giraf
 * web system. It contains automated facilities for retrieving field lists and
 * handling changes in them like record sets in ODBC. This unified inheritance
 * between all table-sourced container classes should simplify maintenance and
 * extension.
 * \todo Implement static initialisation of all subclasses of GirafRecord, so
 * their primary keys and table names can be accessed without an instance.
 */
abstract class GirafRecord
{
    /**
     * Used when needing a recordset returned only as primary keys.
     */
    const RETURN_PRIMARYKEY = 0;
    
    /**
     * Used when needing a recordset returned as full records.
     */
    const RETURN_RECORD = 1;

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
     * List of fields in the underlying table of the class.
     */
    protected static $fields;
    
    /**
     * Name of the subclass that inherited this GirafRecord.
     */
    protected static $subClass = null;
    
    /**
     * Name of the table that data comes from. Since merely changing the
     * variable does very little, it is hidden from subclasses. Use
     * setSourceTable to make a proper change.
     * \sa GirafRecord::setSourceTable()
     */
    private static $sourceTable = null;
      
    /**
     * Whether the current record object is new (that is, not yet in the
     * database) or old (that is, pulled from the database.).
     * This variable will be true only when id == -1.
     */
    public function isNewRecord()
    {
        return $this->id == -1 ? true : false;
    }
    
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
     * The static constructor must be defined in subclasses and must be called
     * by the class definitions' file. There is no method of forcing the second
     * requirement, but the code will fail miserably if you try :D
     */
    protected static abstract function staticConstructor();
    
    /**
     * Sets all the static data associated with a given record subclass. We
     * define the staticConstructor as an abstract method to force subclasses to
     * register themselves with this mechanism. A powerful trait of this
     * approach is that it frees up the instance constructors to do less
     * redundant work and more specific work.
     * \param $table Name of the table that contains the underlying data.
     * \param $subclass Name of the subclass of GirafRecord that calls this constructor.
     */
    protected static function setStatics($table, $subclass)
    {
        echo "Setting up as $subclass";
        // Set subclass data for later instantiations.
        self::$subClass = $subclass;
        self::$sourceTable = $table;
        self::setSourceTable();
    }
    
    public function __construct()
    {
        $this->setDataArrays();    
    }
    
    /**
     * Returns the name of the table associated with this record type.
     * \return The name of the table as it is found in the database.
     */
    public function getSourceTable()
    {
        return self::$sourceTable;
    }
    
    /**
     * Returns the name of the primary key on the database table for this record
     * type.
     * \return The primary key's field name as it is found in the database.
     */
    public function getPrimaryKey()
    {
        return self::$primaryKey;
    }
    
    /**
     * Resets the local data and dirty_bits arrays to an initial state.
     */
    protected function setDataArrays()
    {
        /* We dynamically prep the internal data array with the exact
         * field names from the database itself.
         */
        $this->data = Array();
        $this->dirty_bits = Array();
        
        foreach (self::$fields as $field)
        {
            $this->data[$field] = "";
            $this->dirty_bits[$field] = false;
        }
    }
    
    /**
     * Sets the class' source table information, field data and such.
     * It should never be necessary to call this function more than once in 
     * a class' execution lifetime.
     * \param $table Name of the table to scan in.
     */
    protected function setSourceTable($table = null)
    {
        // Set subclass data for later instantiations. If it wasn't set, get it
        // from the class itself.
        
        if (isset($table)) self::$sourceTable = $table;
        else $table = self::$sourceTable;
        
        /**
         * The DESCRIBE statement is akin to a SELECT statement, only for table structure, not table data.
         */
        $result = sql_helper::selectQuery("DESCRIBE " . $table);
        
        if (!$result)
        {
            throw new Exception("The users table '" . $table . "' does not exist!");
        }
        
        $iter = 0;
        $fields = array();
        while ($row = $result->fetch_assoc())
        {
            self::$fields[$iter++] = $row["Field"];
            
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
        // First check if the universal id value is requested. This is shorthand for this->data[$primaryKey]
        // echo $key;
        if ($key == "id")
            return $this->data[self::$primaryKey];
        // Second, check if the requested key is another field. Throw a antrum if not.
        elseif (!array_key_exists($key, $this->data))
            throw new Exception("The requested key does not exist.");
        // Finally, return the found value.
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
        if ($key == self::$primaryKey)
            throw new Exception("The primary key value is write-protected.");
        if (!array_key_exists($key, $this->data))
            throw new Exception("The requested key does not exist.");
        else
        {
            $this->data[$key] = $val;
            $this->dirty_bits[$key] = true;
        } 
    }
    
    /**
     * Sets the primary key value of the record. This must be a protected
     * action, as the key is considered write-protected for the lifetime of the
     * affected row.
     */
    private function setId($id)  
    {
            $this->data[self::$primaryKey] = $id;
    }
    
    /**
     * Retrieves an instance of the object from the database, or an empty
     * object if not set.
     * \param $id If set, the id will be retrieved from the database. if omitted, a new object is created.
     * \return A new object of the proper subclass. If an id was requested, the data fields will be set. If not, an empty object is returned.
     */
    protected static function getInstance($id = null)
    {
        // Use reflection to get subclass info.
        $refl = new ReflectionClass(self::$subClass);
        
        // Create a new subclass instance.
        $newU = $refl->newInstance();
    
        // Quick stop. If no ID was requested. Quickly create something new.
        if (!isset($id))
        {
            // $newU->__set(self::$primaryKey, -1); // Set the primary key to a known invalid value, -1.
            $newU->setId(-1);
            return $newU;
        }
        else
        {
            // Retrieve on hit matching the user.
            $result = sql_helper::selectQuery("SELECT * FROM " . self::$sourceTable . " WHERE " . self::$primaryKey . "=$id");
            
            // If an error occurred or we got no hits, return false.
            if (!$result || $result->num_rows == 0) return false;
            
            // Retrieve the result.
            $row = $result->fetch_assoc();
            
            // Insert query data into the new instance.
            foreach ($row as $key=>$val)
            {
                if($key != self::$primaryKey) $newU->$key = $val;
                else $newU->setId($val);
            }
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
        if ($this->data[$this->primaryKey] > 0) commitUpdate();
        else commitInsert();
    }
    
    /**
     * Called by GirafRecord::commit() when the data is an update of current record.
     */
    private function commitUpdate()
    {
        $sql = "UPDATE " . self::$sourceTable . " SET (";
        
        foreach ($this->dirty_bits as $name=>$isDirty)
        {
            if($isDirty)
            {
                $sql .= "$name='" . $this->data[$name] . "',";
            }
        }
        $sql = substr($sql, 0, strlen($sql) - 1); // Remove final colon.
        
        $sql .= ") WHERE " . self::$primaryKey . "=" . $this->data[self::$primaryKey];
    
        sql_helper::updateQuery($sql);
    }
    
    /**
     * Called by GirafRecord::commit() when the data is a new record.
     */
    private function commitInsert()
    {
        $sql = "INSERT INTO " . self::$sourceTable . " (";
        
        $values = "";
        
        // Insert column names.
        foreach ($this->data as $name=>$val)
        {
            $sql .= "$name, ";
            $values .= "'$val',";
        }
        
        $sql = trim($sql, ","); // Trim final comma.
        $value = trim($value, ","); // Trim final comma.
        
        
        $sql .= ") VALUES ($values)";
    
        sql_helper::insertQuery($sql);
    }
    
    /**
     * Convenience function to construct and return the condition in a WHERE
     * statement that refers specifically to the current instance's id.
     */
    protected function getSelfCondition()
    {
        return self::$primaryKey . "=" . $this->data[self::$primaryKey];
    }
    
    /**
     * Retrieves a set of records from the source table either by their primary
     * keys or complete copies.
     * \param $condition The condition to pull the records from. This must be a valid part of SQL's "WHERE".
     * \param $return_as The type of return type needed.
     *  Valid options are RETURN_PRIMARYKEY (which returns an array of keys) and
     *  RETURN_RECORD (which returns an array of SimpleRecord objects, each containing one record).
     * \warning On sufficiently large tables, using RETURN_RECORD (and at some point RETURN_PRIMARYKEY) <br>will</br> result in errors. Be sure to place proper restrictions with $condition beforehand.
     */
    public function getRecords($condition = null, $return_as = self::RETURN_PRIMARYKEY)
    {
        $sql = "SELECT " . self::$primaryKey . " FROM " . self::$sourceTable;
        
        if (isset($condition)) $sql .= " WHERE $condition";
        
        $keys = sql_helper::selectQuery($sql);
        
        if (!$keys)
        {
            throw new Exception(sql_helper::getLastError());
        }
        
        $key_array = Array();
        $iter = 0;
        // Turn into array.
        while ($row = $keys->fetch_row())
        {
            $key_array[$iter] = $row[0];
            $iter++;
        }
        
        // At this point, the primary keys are stored in a nice array.
        
        if ($return_as == self::RETURN_PRIMARYKEY) return $key_array;
        
        // If the user wanted more, run a new loop.
        $records = Array();
        $iter = 0;
        foreach ($key_array as $key)
        {
            $records[$iter] = self::getInstance($key);
            $iter++;
        }
        
        var_dump($records);
        
        return $records;
    }
}
?>
