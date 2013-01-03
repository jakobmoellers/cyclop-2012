<?php

// Can't access this file directly!
if(preg_match("/".basename(__FILE__)."/", $_SERVER['SCRIPT_NAME'])) header("Location: index.php");

// Your page content
echo "Hello ".$SUMO['user']['user']." ! You are on PAGE 1 <hr>";

echo "Go to <a href='?page=page2'>Page 2</a> <br><br>";

// Logout link
echo "<a href='?sumo_action=logout'>Logout</a>";


echo "<br><br>Look the \$SUMO['user'] array to get current user informations:";

echo "<pre>";
print_r($SUMO['user']);
echo "</pre>";

?>