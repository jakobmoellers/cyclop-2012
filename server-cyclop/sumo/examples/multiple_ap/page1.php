<?php

/**
 * Set your correct path to sumo.php
 */
require "../../sumo.php";


// Your page content
echo "Hello ".$SUMO['user']['user']." ! You are on PAGE 1 <hr>";

echo "Go to <a href='index.php'>INDEX</a> <br><br>";
echo "Go to <a href='page2.php'>Page 2</a> <br><br>";

// Logout link
echo "<a href='?sumo_action=logout'>Logout</a>";


echo "<br><br>Look the \$SUMO['user'] array to get current user informations:";

echo "<pre>";
print_r($SUMO['user']);
echo "</pre>";

?>