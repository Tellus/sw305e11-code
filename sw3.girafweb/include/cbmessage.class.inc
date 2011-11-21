<?php

require_once(__DIR__ . "/record.class.inc");
require_once(__DIR__ . "/constants.inc");

/**
 * Data container class for contactbook messages.
 * \note Currently resides in the reserved area of the document root
 * (./include), should be moved to its modules particular dir.
 */
class ContactbookMessage extends GirafRecord
{
	const ORDER_ASCENDING = 0;
	const ORDER_DESCENDING = 1;
	
    public static function getSourceTable()
    {
        return "cbMessages";
    }
    
    public static function getPrimaryKey()
    {
        return "messageId";
    }
    
    /**
     * Retrieves a contactbook message with the given ID.
     * \return The message in question.
     */
    public static function getMessage($mId)
    {
        return parent::getInstance($mId);
    }
    
    /**
     * Retrieves any replies to the current instance.
     * \return Array of message ID's that are replies to this one. You
     * can discern newer replies simply by higher ID's.
     * */
    public function getReplies()
    {
		return self::getMessages("msgParentKey=" . $this->id, null, self::RETURN_RECORD);
	}
	
	/**
	 * Retrieves a set of messages.
	 * \param $cond Condition for retrieval.
	 * \param $order The order of the entries, sorted on their date.
	 * \param $returnAs Return type.
	 * \return Returns either an array of message or primary keys
	 * depending on $returnAs parameter.
	 * \todo Implement the ordering parameter.
	 * */
	public static function getMessages($cond = "", $order = self::ORDER_DESCENDING, $returnAs = self::RETURN_PRIMARYKEY)
	{
		return parent::getRecords($cond, $returnAs);
	}
}

?>
