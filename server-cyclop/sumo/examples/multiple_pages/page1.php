<?php

/**
 * Remember that each authenticated page require an access point
 */

// Set your correct path to sumo.php
require "../../sumo.php";


// Your page content
echo "Hello ".$SUMO['user']['user']." ! You are on PAGE 1 <hr>";

echo "Go to <a href='page2.php'>Page 2</a> <br><br>";


// Common content between page1 and page2
include "common_content.php";

?>