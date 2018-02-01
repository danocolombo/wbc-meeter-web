<?php

/*
 * This is used to consolidate the mysql information
 */
define('DB_NAME','dcolombo_wbc');
define('DB_USER', 'dcolombo_wbc');
define('DB_PASSWORD','servant88');
define('DB_HOST', 'localhost');
//connect to the database
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
?>
