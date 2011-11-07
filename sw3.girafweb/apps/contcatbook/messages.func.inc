<?php
 
require_once("sql_helper.inc");
require_once("constants.inc");

/*
* Get the subject of a message by its messageId
* \param The id of the message
* \return Returns the subject of the message
*/

public static function getMsgSubject($msgId)¨
{
	$result = sql_helper::selectQuery("SELECT msgSubject FROM" . TABLE_CBMESSAGES . "WHERE messageId=" . $msgId);
	
	if (!$result) return false;
	
	return $result;
	
}

/*
* Get the time that the message i added
* \param The id og the message
* \return the date of the message
*/

public static function getMsgDate($msgId)
{
	$result = sql_helper::selectQuery("SELECT msgTimestamp FROM" . TABLE_CBMESSAGES . "WHERE messageId=" . msgId);
	
	if(!$result) return false;
	
	return $result;

}

?>