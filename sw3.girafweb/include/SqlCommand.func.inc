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
}

?>
