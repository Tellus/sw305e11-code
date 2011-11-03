<?php

/* The config file should, quite simply, set constants that will be used throughout
 * the execution of the scripts and that are of global interest.
 */

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

// Defines to make them globally available constants.
define("db_user", $db_user);
define("db_pass", $db_pass);
define("db_db", $db_db);
define("db_host", $db_host);
define("db_port", $db_port);
define("db_table_prefix", $db_table_prefix);

?>
