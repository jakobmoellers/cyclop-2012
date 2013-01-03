<?php

/**
 * Remember that each authenticated page require an access point
 */

/**
 * Set your correct path to sumo.php
 * using require_once because must to be included only one time
 */
require_once "../../sumo.php";

echo "This is the content of common_content.php";

// Logout link
echo "<a href='?sumo_action=logout'>Logout</a>";

echo "<br><br>Look the \$SUMO['user'] array to get current user informations:";

echo "<pre>";
print_r($SUMO['user']);
echo "</pre>";

?>