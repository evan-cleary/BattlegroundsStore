<?php

$hostname = 'localhost';             // Your MySQL hostname. Usualy named as 'localhost', so you're NOT necessary to change this even this script has already online on the internet.
$dbname   = 'battlegrounds';         // Your database name.
$username = 'root';                  // Your database username.
$password = 'alpine';                // Your database password. If your database has no password, leave it empty.
$tablePrefix = 'battlegrounds_';     // If you changed your prefix in the battlegrounds config make this match.

// Let's connect to host
$db_conn = mysql_connect($hostname, $username, $password) or DIE('Connection to host is failed, perhaps the service is down!');
// Select the database
mysql_select_db($dbname) or DIE('Database name is not available!');

?>