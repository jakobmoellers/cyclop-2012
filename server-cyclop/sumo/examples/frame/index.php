<?php

/**
 * Load SUMO after login
 *
 * IMPORTANT: this is the FIRST line of your page
 *
 * Remember to set "iframe" theme for login frame
 */
//if($_COOKIE['loggedin'] == 1 || isset($_GET['sumo_action'])) require "../../sumo.php";
if(isset($_GET['sumo_action'])) require "../../sumo.php";

// Your page content
echo "My content...<br><br><font color='red'>iframe login</font><br>";
// ...

// Login frame
echo '<iframe src="login_frame.php" width="265" height="150" style="border: 1px solid red" frameborder="0" scrolling="no" name="login"></iframe>';

// Other content
// ...
echo "<br><br> end.";

?>