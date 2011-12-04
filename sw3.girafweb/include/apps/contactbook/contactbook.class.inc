<?php

require_once(INCDIR . "record.class.inc");

// Contactbook-specific defines that cannot reside in constants.php

// Tables.
define('TABLE_CB_MESSAGES', 'cbMessages');
define('TABLE_MESSAGE_READ_STATUS', 'cbMessagesRead');

// Hard limit on how far down through the message list we even bother
// looking for read status (at most 10 unread messages).
define('APP_CB_READBY_LIMIT', 10);

/**
 * Data container class for contactbook messages.
 */
class ContactbookMessage extends GirafRecord
{
	const ORDER_ASCENDING = 0;
	const ORDER_DESCENDING = 1;
	
	protected $readByUsers = array();
	
    public static function getSourceTable()
    {
        return TABLE_CB_MESSAGES;
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
		// Get the message itself.
		$ins = parent::getInstance($mId);
		
        // Get all the users that have read that message.
        $ins->readByUsers = MessageReadStatus::getReadByMessage($mId, APP_CB_READBY_LIMIT);
        
        return $ins;
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
		$replies = parent::getRecords($cond, $returnAs);
		
		foreach ($replies as $reply)
		{
			$reply->readByUsers = MessageReadStatus::getReadByMessage($reply->id, APP_CB_READBY_LIMIT);
		}
		
		return $replies;
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
	
	/**
	 * Checks to see whether a given user has read the currently loaded
	 * message.
	 * \param $userId The user to check up against. If omitted, the
	 * currently logged in user is used (if possible).
	 * \return True if the message has been read, false otherwise.
	 * \throws Exception Generic exception is thrown if $userId is
	 * omitted and no user is logged in.
	 * */
	public function isRead($userId)
	{		
		return in_array($userId, $this->readByUsers, true);
	}
	
	/**
	 * Changes read status of the message for a given user.
	 * \param $userId The user to change status for.
	 * \param $isRead The new status of the message. Defaults to true
	 * ('read').
	 * */
	public function setRead($userId, $isRead = true)
	{
		MessageReadStatus::setReadStatus($this->id, $userId, $isRead);
	}
}

class MessageReadStatus extends GirafRecord
{
	public static function getSourceTable()
	{
		return TABLE_MESSAGE_READ_STATUS;
	}
	
	public static function getPrimaryKey()
	{
		return 'readId';
	}
	
	/**
	 * Changes the read/unread status of a message for a particular
	 * user.
	 * \param $msg Message ID.
	 * \param $user User ID.
	 * \param $status The new status, true or false.
	 * */
	public static function setReadStatus($msg, $user, $status)
	{
		if (!is_bool($status)) throw new Exception();
		
		if ($status)
		{
			sql_helper::insertQuery('INSERT INTO ' . MessageReadStatus::getSourceTable() . " (msgKey, userKey) VALUES ($msg, $user)");
		}
		else
		{
			sql_helper::deleteQuery("DELETE * FROM " . MessageReadStatus::getSourceTable() . " WHERE (msgKey=$msg AND userKey=$user)");
		}
	}
	
	/**
	 * Retrieves all users that have read a particular message.
	 * \param $msgId Id of the message to chekc status for.
	 * \return Array of user ids that have read the message.
	 * */
	public static function getReadByMessage($msgId, $limit = null)
	{
		return self::getReadByProxy("userKey", "msgKey", $msgId, $limit);
	}
	
	/**
	 * Determines whether a message has been read by a user.
	 * \param $msg Message ID.
	 * \param $user User ID.
	 * \return True if the message has been read by the user, false
	 * otherwise.
	 * */
	public static function isReadBy($msg, $user)
	{
		$res = sql_helper::selectQuery("SELECT readId FROM cbMessagesRead WHERE (userKey=$user AND msgKey=$msg)");
		
		if ($res === false) return false;
		elseif ($res->num_rows === 0) return false;
		else return true;
	}
	
	/**
	 * Retrieves all messages that have been read by a particular user.
	 * \param $userId Id of the user to search on.
	 * \param $limit Optionally limit the number of requested rows.
	 * Very useful once the database starts bloating.
	 */
	public static function getReadByuser($userId, $limit = null)
	{
		return self::getReadByProxy("msgKey", "userKey", $userId, $limit);
	}
	
	private static function getReadByProxy($lfield, $rfield, $cond, $limit = null)
	{
		if (self::_is_limit_valid($limit)) $postfix = " LIMIT 0,$limit";
		else $postfix = "";
		
		$sql = sql_helper::selectQuery("SELECT $lfield FROM cbMessagesRead WHERE $rfield=$cond $postfix");
		
		$ret = array();
		if ($sql == false) return $ret;
		
		while ($row = $sql->fetch_row())
		{
			$ret[] = $row[0];
		}
		
		return $ret;
	}
	
	/**
	 * Determines whether a given limit value (as passed to either
	 * getReadByUser or getReadByMessage) is valid.
	 * \param $limit The value as it was passed to the function.
	 * \param $throwOnFalse If true, the function will throw an
	 * exception instead of merely returning false.
	 * \return True if the value was valid, false otherwise.
	 * \note Note that the function returns nothing if $throwOnFalse
	 * was set to true.
	 * */
	protected static function _is_limit_valid($limit = null, $throwOnFalse = false)
	{
		if (!isset($limit))
		{
			if ($throwOnFalse) throw new Exception("Limit was not set");
			else return false;
		}
		elseif (is_numeric($limit) && $limit >= 1)
		{
			return true;
		}
		else
		{
			if ($throwOnFalse) throw new Exception('Limit was not of a valid type, \'' . get_class($limit) . '\'.');
			else return false;
		}
	}
}

?>
