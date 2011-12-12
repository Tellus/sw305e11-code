<?php

/* The config file should, quite simply, set constants that will be used throughout
 * the execution of the scripts and that are of global interest.
 */

/** Database values **/
// Username for the MySQL database.
$db_user = "giraf_web";

// Password for that user.
$db_pass = "cookie";

// The database to work from.
$db_db = "girafplace";

// The hostname of the database.
$db_host = "homestead.dk";

// Optionally, the port.
$db_port = 3306;

// Optionally, a global table prefix.
$db_table_prefix = ""; 

/** Optional configs **/
/* Your own salt to improve entropy when hashing passwords. Warning! Changing
 * this AFTER taking the system into use will immediately make all existing
 * logins impossible to access without admin intervention.
 */
$crypt_salt = "ergf0h34280hufdg097h324";

/**
 * Define number of rounds the crypt function should run a string before
 * completion.
 * WARNING: Changing this after users have been registered, those users need new
 * passwords as the old passwords cannot be verified any more.
 */
$crypt_rounds = 5000;

// Define the base url so we don't have to figure it out.
$base_url = "http://giraf.homestead.dk/";

// Defines to make them globally available constants.
define("db_user", $db_user);
define("db_pass", $db_pass);
define("db_db", $db_db);
define("db_host", $db_host);
define("db_port", $db_port);
define("db_table_prefix", $db_table_prefix);
define("crypt_salt", $crypt_salt);
define("crypt_rounds", $crypt_rounds);
define("SITE_BASE_URL", $base_url);

?>
