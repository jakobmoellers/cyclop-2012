<?php

/**
 * Set your correct path to sumo.php
 */
require "../../sumo.php";

// Your page content
echo "My content... <hr>";


if(isset($_GET['page']))
{
	switch ($_GET['page'])
	{
		case 'page1': 
			$page = "page1.php"; 
			break;
			
		case 'page2': 
			$page = "page2.php"; 
			break;
			
		default:
			die("Page not found!");
			break;
	}
	
	require $page;
}
else {

	echo "<ul>
			<li><a href='?page=page1'>Page 1</a></li>
			<li><a href='?page=page2'>Page 2</a></li>
		  </ul>";
	
	// Logout link
	echo "<br><br><a href='?sumo_action=logout'>Logout</a>";
}

?>