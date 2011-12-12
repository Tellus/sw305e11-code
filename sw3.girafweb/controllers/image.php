<?php

require_once(INCDIR . "controller.php");
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
	
	/**
	 * The view action will retrieve binary data from the model and
	 * attempt to send it correctly for display in the browser. This
	 * approach gives us full control of who can access images, 
	 * explicitly keeping out pure-url retrievals without a valid
	 * session cookie.
	 **/
	public function view($params = array())
	{
		if (!array_key_exists("param0", $params)) throw new Exception ("No image requested");
		
		$imgId = $params["param0"];
		$img = GirafImage::getGirafImage($imgId);

		if (!$img)
		{
			header("Content-type: image/png");
			// Could not find image. Go to fallback mode and display pretty error image.
			$imgData = imagecreate(120, 30);
			$background_color = imagecolorallocate($imgData, 0, 0, 0);
			$text_color = imagecolorallocate($imgData, 233, 14, 91);
			imagestring($imgData, 1, 5, 5,  "Image not found", $text_color);
			imagepng($imgData);
			imagedestroy($imgData);
		}
		else
		{
			header("Content-type: $img->imageMimeType");
			//header("Content-length: $img->imageSize");
			echo $img->imageData;
		}
	}
}

?>
