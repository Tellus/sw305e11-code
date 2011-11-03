<?php
 
require_once("sql_helper.inc");
require_once("constants.inc");

/*
*
*
*/

public static function getMsgSubject($msgId)¨
{
	$result = sql_helper::selectQuery("SELECT msgSubject FROM" . TABLE_CBMESSAGES . "WHERE messageId=" . $msgId);
	
	if (!$result) return false;
	
	return $result;
	
}

public static function getMsgDate($msgId)
{
	$result = sql_helper::selectQuery("SELECT msgTimestamp FROM" . TABLE_CBMESSAGES . "WHERE messageId=" . msgId);
	
	if(!$result) return false;
	
	return $result;

}

?>