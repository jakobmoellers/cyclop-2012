<?php
/* 
 * Enables querying and executing the potspot database
 * supported methods:
 * pgqueryresult query($query)
 * void execute($query)
 *
*/

require 'conf.php';

//helper function to ease postgresql queries
function query($query) {
	global $dbhost, $dbname, $dbuser, $dbpw;
	//connect with db
	$dbconn = pg_connect("host=".$dbhost." dbname=".$dbname." user=".$dbuser." password=".$dbpw)
				or die('Verbindungsaufbau fehlgeschlagen: ' . pg_last_error());
	$results = pg_query($query) or die(utf8_decode(pg_last_error()));
	pg_close($dbconn);
	return $results;
}


/**
Queries the database if there are results for a certain query.
Param 
$query - String containing a valid pgresql query
Return 
true if there are results 
false if there are not.
*/
function exists($query) {
	global $dbhost, $dbname, $dbuser, $dbpw;
	$dbconn = pg_connect("host=".$dbhost." dbname=".$dbname." user=".$dbuser." password=".$dbpw)
				or die('Verbindungsaufbau fehlgeschlagen: ' . pg_last_error());
	$results = pg_query($query) or die(utf8_decode(pg_last_error()));
	$count = pg_num_rows($results);
	
	pg_close($dbconn);
	return ($count > 0);
}

//executes a SQL query
function execute($query) {
	global $dbhost, $dbname, $dbuser, $dbpw;
	//connect with db
	$dbconn = pg_connect("host=".$dbhost." dbname=".$dbname." user=".$dbuser." password=".$dbpw)
				or die('Verbindungsaufbau fehlgeschlagen: ' . pg_last_error());
	$results = pg_query($query) or die(pg_last_error());
	pg_close($dbconn);
}
?>