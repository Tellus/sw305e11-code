<?php

class newsHandler
{
	private $news;

	public function __construct($userID)
	{
		$this->news = getUserNews($userID);
	}
	
	
}


?>