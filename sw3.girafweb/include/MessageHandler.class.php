<?php
require_once(__DIR__ . "/cbmessage.class.inc");
require_once(__DIR__ . "/image.class.inc");

/**
*
*/
class ContactbookMessageHandler
{
	private $id;
	private $message;
	
	/**
	* \param The message id
	*/
	public function __construct($ID)
	{
		$this->id = $ID;
		$this-> message = ContactbookMessage::getMessage($ID);
	}	
	
    /**
     * Retrieves any replies to the current instance.
     * \return Array of message ID's that are replies to this one. You
     * can discern newer replies simply by higher ID's.
     */
	public function getReply()
	{
		ContactbookMessage::getReplies();
	}
	
	/**
	 * Retrieves all images associated with the message.
	 * \return Array of paths to images.
	 */
	public function getImages()
	{
		$image=ContactbookMessage::getImages();
		//evt mere
		return $image;
	}
	
	/**
	* \return this message id
	*/
	public function messageId()
	{
		return $this->messageId;
	}
	
	/**
	* \return Key to the message that this is a reply to. Null if it is a prime message.
	*/
	public function getParentKey()
	{
		$temp = $this->message;
		return $temp->msgParentKey;
	}
	/**
	* \return A child key
	*/
	public function getMsgChildKey()
	{
		$temp = $this->message;
		return temp->msgChildKey;
	}

	/**
	* \return Key to the user that posted this message.
	*/
	public function getMsgUserKey()
	{
		$temp = $this->message;
		return temp->msgUserKey;
	}	
	
	/**
	* \return The timestamp when the message was made 
	*/
	public function getMsgTimestamp()
	{
		$temp = $this->message;
		return temp->msgTimestamp;
	}
	
	/**
	* \return The message's subject 
	*/
	public function getMsgSubject()
	{
		$temp = $this->message;
		return temp->msgSubject;
	}	

	/**
	* \return The message's text body 
	*/	
	public function getMsgBody()
	{
		$temp = $this->message;
		return temp->msgBody;
	}
	
	/**
	 * (Very) shorthand for creating a reply to this message.
	 * \param $userId Id of the poster.
	 * \param $childId Id of the child this is for.
	 * \param $subject Message subject.
	 * \param $body Message body.
	 * \return Id of the new message on success, false on failure.
	 */
	public function replyToMessage($uId, $cId, $subject, $body)
	{
		return ContactbookMessage::createNewMessage($uId, $cId, $subject, $body, $this->id);

	}
	/**
	 * \param $path Path to the file. Absolute or relative to the
     * location of index.php
     * \param $msgId Id of the message that the image belongs to.
     * \param $text Text for the image.	
	 * \return Id of the image or false.
	 */
	public static function addImageToAMessage($path, $msgId, $text)
	{
		 $image = GirafImage::createMessageImage($path, $msgId, $text);
		 if(!$image) return false;
		 return $image;
	}

	/**
	 *  Create new messages
	 * \param $userId Id of the poster.
	 * \param $childId Id of the child this is for.
	 * \param $subject Message subject.
	 * \param $body Message body.
	 * \param $parent Optional, the message ID that this message is a reply to.
	 * \return Id of the new message on success, false on failure.
	 */
	public static createNewMessage($uId, $cId, $subject, $body, $parent = null)
	{
		return ContactbookMessage::createNewMessage($uId, $cId, $subject, $body, $parent);
	}
	
	
}

?>