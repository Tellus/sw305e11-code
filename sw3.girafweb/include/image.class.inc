<?php

require_once(__DIR__ . "/record.class.inc");
require_once(__DIR__ . "/util.func.inc");
require_once(__DIR__ . "/constants.php");

/**
 * The GirafImage class is a complex enhancement of the GirafRecord class.
 * Beyond simply allowing manipulation of image resources in the database, it
 * supports IMagick manipulation of the raw image data.
 * \todo Write code that properly handles the various URI types.
 * \warning Unfinished! URI handling is incomplete.
 */
class GirafImage extends GirafRecord
{
    // Rotate the image 90 degress clockwise.
    const ROTATE_90 = 90;
    
    // Rotate the image 180 degrees clockwise.
    const ROTATE_180 = 180;
    
    // Rotate the image 270 degrees clockwise.
    const ROTATE_270 = 270;

    /**
     * Denotes that the storage is accessible directly for the web server, i.e. it requires no scripts to retrieve the bitmap data, only a web-accessible path.
     */
    const URI_WEB = "http";
    
    /**
     * Identical to STORE_WEB, except it references a secure storage solution / alternate port.
     * \see{StorageTypes::STORE_WEB}
     */
    const URI_WEB_SECURE = "https";
    
    /**
     * The stored data is locally available on the file system, but not
     * in a location accessible by the webserver by itself.
     */
    const URI_LOCAL = "file";
    
    /**
     * The data is remotely stored on an FTP server.
     */
    const URI_FTP = "ftp";
    
    /**
     * IMagick object with the image data for manipulation.
     */
    private $imageData;
    
    /**
     * Path to the directory where images will be stored.
     */
    public $imagePath;

    /**
     * Base constructor. Sets a few nice default values, but otherwise is empty.
     */
    public __construct()
    {
        $imagePath = setImagePath();
    }
    
    /**
     * Creates a new GirafImage and loads the image from the given uri.
     * \param $fileUri URI to the image.
     */
    public __construct($fileUri)
    {
    
    }
    
    /**
     * Creates a new GirafImage based on data uploaded through the POST
     * mechanism.
     * \param $postData Data directly from the $_POST variable.
     * \param $imgName Name of the file. If omitted, a random identifier is generated.
     * \return New GirafImage instance with the image data stored both on disk and in database.
     */
    public static function createFromPost($postData, $imgName = "")
    {
        $newI = new GirafImage();
    
        // Generate a random id if none was passed.
        if ($imgName = "")
        {
            $imgName = uniqid("", true); // Set the image name to something random if omitted.
        }

        // If the file already exists, append a number until we get a hit. Simple hashing, woot!
        $postfix = "";
        while(self::imageExists($newI->imagePath . $imgName . $postfix))
        {
            $postfix = $postfix == "" ? 0 : $postfix + 1;
        }
        
        // Save the result.
        $finalPath = $newI->imagePath . $imgName . $postfix;
    
        // Register the new image with the database. We do this before saving it
        // to disk in case a sudden error occurs. We don't want random junk.
        sql_helper::insertQuery("INSERT INTO " . TABLE_IMAGE_RESOURCES . " (imgPath) VALUES (file://$finalpath)");

        move_uploaded_file($postData, $finalpath);
        
        $newI = new GirafImage($finalpath);
    }
    
    /**
     * Checks if a particular image exists.
     * \param The entire URI to the image. This ensures that local and remote file systems are treated individually.
     * \return If the path is in use according to the database, true. False otherwise.
     */
    public static function imageExists($path)
    {
        $result = sql_helper::selectQuery("SELECT * FROM " . TABLE_IMAGE_RESOURCES . "WHERE imgUri='$path'");
        
        // If an error occurred, report it. If our query returned results, return true.
        // In all other cases, return false.
        if (!$result) throw new Exception("Something went wrong: " . $result->error);
        else if ($result->num_rows > 0) return true;
        else return false;
    }
    
    /**
     * Sets the path used by the object to store the image. This is only the
     * directory portion of the path, not the file name itself.
     */
    protected static function setImagePath($path = false)
    {
        // Get the global path if omitted.
        if (!$path) $path = GirafSettings::getSetting(SETTING_LOCAL_IMAGE_PATH);

        // Ensure trailing slash.
        $path = util::trailingSlash($path);
        
        // Set.
        $this->imagePath = $path;
    }
    
    /**
     * Stores the cached image data into a specific storage area.
     * \todo Design not finished!
     */
    public function storeImg($storageType = StorageTypes::STORE_WEB)
    {
    
    }
    
    /**
     * Retrieves image data from the database.
     * \param imageId The ID of the image to retrieve.
     * \return A new GirafImage instance with values initialised or false on error.
     */
    public static function getGirafImage($imageId)
    {
        parent::getInstance($imageId);
        
        
    }
    
    /**
     * Rotates the image.
     * /param $degrees Degrees of rotation. Use the ROTATE_* constants for quick denominations.
     */
    public function rotateImage($degrees = self::ROTATE_90)
    {
    
    }
}

?>
