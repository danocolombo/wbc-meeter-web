<?php
global $connection;
/*-----------------
 * database.php
 * ========================================
 * This is authentication method learned from
 * http://www.developerdrive.com/2013/05/creating-a-simple-to-do-application-–-part-3/
 * 
 */
if ( isset( $connection ) )
	return;
mysqli_report(MYSQLI_REPORT_STRICT);

define('DB_HOST', 'localhost');
define('DB_USER', 'dcolombo_wbc');
define('DB_PASSWORD', 'servant88');
define('DB_NAME', 'dcolombo_wbc');

$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if (mysqli_connect_errno()) {		
	die(sprintf("[database.php] Connect failed: %s\n", mysqli_connect_error()));
}
?>