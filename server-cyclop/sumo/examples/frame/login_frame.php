<?php

/**
 * Set your correct path to sumo.php
 */
require "../../sumo.php";

if($sumo_access == 'LOGIN') 
{	
	sleep(3); // ...for login delay, BAD temporary solution  :(
	
	echo "<script language='javascript' type='text/javascript'>"
		."window.top.location='index.php';"
		."</script>";
}

// Logout
echo "<a href='#' onclick='javascript:window.top.location=\"index.php?sumo_action=logout\"'>Logout</a>";

?>