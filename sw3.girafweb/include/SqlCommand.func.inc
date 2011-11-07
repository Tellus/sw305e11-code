<?php

/**
 * This class mimics likened commands in larger database frameworks in that you
 * cannot execute an SQL query directly, but must submit parameters to it,
 * execute it and finally retrieve the results. SqlCommand must be inherited to
 * gain any proper functionality.
 */
abstract class SqlCommand
{
    /**
     * The actual SQL command string. Should be pieced together by the rest of
     * this class.
     */
    protected $sql = "";

    /**
     * The MySQLi_Result object from an executed statement.
     */
    protected $result;

    public function execute()
    {
        self->$result = sql_helper::selectQuery($sql);
    }
    
    public function getResult()
    {
        return this->$result;
    }
    
    /**
     * Adds a new column parameter to the statement. 
     * \param col Mandatory. Denotes the column that the parameter affects.
     * \param val Optional in certain statements. Denotes the value to put on the column.
     */
    abstract public function addParameter($col, $val = null);
    
    /**
     * Adds a condition to the statement (these follow the WHICH part).
     * \param col The column you want to have tested.
     * \param val The value you're testing against.
     * \param condType The type of conditional. String. Valid types are the binary operators for SQL.
     */
    abstract public function addCondition($col, $val, $condType);
    
    /**
     * Constructs the complete SQL statement from the values given so far.
     * \return A complete SQL statement, ready for execution.
     * \warning At this time, we cannot guarantee that the statement will run without error, mostly due to injection attempts and faulty type values.
     */
    abstract protected function combine();
}

/**
 * A simple wrapper for the UPDATE statement in SQL. Takes a table, a list of field=value pairs and a set of conditions and attempts to combine them into a valid SQL statement, ready to be run in sql_helper.
 */
class SqlUpdateCommand extends SqlCommand
{
    /**
     * Array of changed fields. The key is the field name, the value is the new value to put into it.
     */
    private $changes;

    /**
     * Array of conditions for the update.
     */
    private $conditions;

    /**
     * Name of the table we're running an update on.
     */
    public $table;

    /**
     * Creates a new SqlUpdateCommand object.
     * \param $table Name of the table this update will run on.
     */
    function __constructor($table)
    {
        $changes = new Array();
        $conditions = new Array();
        
        $this->$table = $table;
    }

    /**
     * Adds a new change parameter to the list of fields to update.
     * \param $col The Field (column) to update.
     * \param $val The new value of the field.
     * \warning At this time, no type checking is performed prior to executing against the MySQL server.
     */
    public function addParameter($col, $val)
    {
        $changes[$col] = $val;
    }
    
    /**
     * Adds a simple binary condition to the list of conditions for the statement.
     * \param col The field (column) to place a condition for.
     * \param val The comparison value.
     * \param condType The type of comparison operator to use. Valid operators are "=", "<", ">" and the like.
     */
    public function addCondition($col, $valm, $condType = "=")  
    {
        $conditions[$col] = $val;
    }
}

?>
