<?php

require_once("sql_helper.inc");
require_once("constants.inc");

public class MessageData
{
	public $id;
	public $parentKey;
	public $timestamp;
	public $subject;
	public $body;
	
	/*
	* Gets the messages data from the databasetable cbMessages
	* \param Takes the id of a message as input
	* \return Return all the message data as an object of the MessageData class
	*/
	
	public static function getMsgData($msgId)
	{
		sql_helper::selectQuery("SELECT * FROM" . TABLE_CBMESSAGES . "WHERE messageId=" . $msgId);
		
		if(!result) return false;
		
		$row = $result->fetch_assoc();
        $ret = new MessageData();
        $ret->$id = $row["messageId"];
        $ret->$parentKey = $row["msgParentId"];
        $ret->$timestamp = $row["msgTimestamp"];
        $ret->$subject = $row["msgSubject"];
		$ret->$body = $row["msgBody"];
        
        return $ret;
	}

}


?>