<?php

require_once(__DIR__ . "/sql_helper.inc");
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
	 * Retrieves all images associated with the message.
	 * \return Array of paths to images.
	 * */
	public function getImages()
	{
		// Get references.
		$result = sql_helper::selectQuery("SELECT imgKey FROM cbMsgImages WHERE msgKey=" . $this->id);
		
		// Traverse the result, getting all the paths.
		$images = array();
		
		while ($row = $result->fetch_assoc())
		{
			$images[] = sql_helper::simpleQuery("SELECT imgUri FROM imageResources WHERE imgId=" . $row["imgKey"]);
		}
		
		return $images;
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
	
	/**
	 * (Very) shorthand for creating new messages. Really just a proxy
	 * for an insertion statement.
	 * \param $userId Id of the poster.
	 * \param $childId Id of the child this is for.
	 * \param $subject Message subject.
	 * \param $body Message body.
	 * \param $parent Optional, the message ID that this message is a reply to.
	 * \return Id of the new message on success, false on failure.
	 * */
	public static function createNewMessage($uId, $cId, $subject, $body, $parent = null)
	{
		if (isset($parent))
		{
			$sql = "INSERT INTO " . self::getSourceTable() .
					" (msgParentKey, msgChildKey, msgUserKey, msgSubject, msgBodY) VALUES " .
					" ('$parent', '$cId', '$uId', '$subject', '$body')";
		}
		else
		{
			$sql = "INSERT INTO " . self::getSourceTable() .
					" (msgParentKey, msgChildKey, msgUserKey, msgSubject, msgBodY) VALUES " .
					" (NULL, '$cId', '$uId', '$subject', '$body')";
		}
				
		// var_dump($sql);
				
		$res = sql_helper::insertQuery($sql);
		
		if (!$res) die(sql_helper::getErrMsg());
		
		// Else, we assume good.
		// SELECT * FROM `cbMessages` WHERE msgUserKey=1 ORDER BY messageId DESC LIMIT 1
		return sql_helper::simpleQuery("SELECT " . self::getPrimaryKey() . " FROM " . self::getSourceTable() . " WHERE msgUserKey=$uId ORDER BY messageId DESC LIMIT 1");
	}
}

?>
