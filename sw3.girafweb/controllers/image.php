<?php

require_once(INCDIR . "image.class.inc");

/**
 * Support controller for images. Used to upload images via HTTP and
 * retrieve URI's for uploaded images.
 * */
class Image extends GirafController
{
	public function index()
	{
		die("Image module cannot be called directly!");
	}
	
	public function upload($params = array())
	{
		
	}
	
	public function retrieve($params = array())
	{
		
	}
}

?>
