<?php

/**
 * Enum class for GirafImage and other storage classes that use
 * different storage options.
 */
class StorageTypes
{
    /**
     * Denotes that the storage is accessible directly for the web server, i.e. it requires no scripts to retrieve the bitmap data, only a web-accessible path.
     */
    const STORE_WEB = "http";
    
    /**
     * Identical to STORE_WEB, except it references a secure storage solution / alternate port.
     * \see{StorageTypes::STORE_WEB}
     */
    const STORE_WEB_SECURE = "https";
    
    /**
     * The stored data is locally available on the file system, but not
     * in a location accessible by the webserver by itself.
     */
    const STORE_FILE = "file";
    
    /**
     * The data is remotely stored on an FTP server.
     */
    const STORE_FTP = "ftp";
}

class GirafImage
{
    const ROTATE_90 = 90;
    const ROTATE_180 = 180;
    const ROTATE_270 = 270;

    public $id;
    public $uri;
    
    /**
     * Stores the cached image data into a specific storage area.
     * \todo Design not finished!
     */
    public function storeImg($storageType = StorageTypes::STORE_WEB)
    {
    
    }
    
    /**
     * Deploys the image directly onto the webserver, so it is directly accessible by browsers and other net-aware devices.
     * \todo Code it.
     */
    public function deployImage()
    {
    
    }
    
    /**
     * Retrieves image data from the database.
     * \param imageId The ID of the image to retrieve.
     * \return A new GirafImage instance with values initialised or false on error.
     */
    public static function getImage($imageId)
    {
        
    }
    
    public function rotateImage($degrees = self::ROTATE_90)
    {
    
    }
}

?>
