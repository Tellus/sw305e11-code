<?php

require_once(__DIR__ . "/debug_helpers.inc");

// Volatile testing file.

decho ("Welcome, attempting to load up mysql connection...");

require_once("sql_helper.inc");

decho ("Assuming success!");

$result = sql_helper::selectQuery("SELECT * FROM errors");

while ($row = $result->fetch_assoc())
{
    decho($row["errorId"] . ": " . $row["errorMessage"]);
}

require_once("db_user.func.inc");

echo registerNewUser("lindhart2", "pass", "John Borring", "no@where.com");

?>
