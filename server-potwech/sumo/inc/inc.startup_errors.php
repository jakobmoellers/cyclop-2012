<?php

/**
 * Startup errors messages
 * 
 * $errors["ERROR CODE"] = array("SHORT DESC.", "LONG DESC.", "SHOW ALWAYS LONG DESC.") ...
 */
$errors = array(
	'E00000S' => array('Undefined error!', 'No error details', true),
	'E00001S' => array('You have accessed this URL improperly!', 'No error details', true),
	'E00002S' => array('Configuration error!',
				'<code>register_globals</code> must to be OFF!<br />Change it in your <code>php.ini</code>.', true),
	'E00003S' => array('Database connection failed!',
				'<ol><li>Verify if database is running</li><li>If database is remote verify if accept remote '
				.'connections (in MySQL set bind_address=<i>&lt;YOUR DATABASE SERVER ADDRESS&gt;</i>  for PostgreSQL add a line like "'
	    			.'host    all         all         <i>&lt;YOUR CLIENT IP ADDRESS&gt;</i>/<i>255</i>          trust" to pg_hba.conf)</li>'
	    			.'<li>Control <code>$sumo_db</code> '
	    			.'parameters in SUMO configuration file: <code>'.SUMO_PATH.'/configs/config.database.php</code></li>'
	    			.'<li>Make sure your database user can connect from remote address, look at your db privileges</li>'
	    			.'<li>Reload page</li>'
	    			.'<li>If don&#39;t works verify if you have the required php modules for your database</li></ol>'),
	'E00004S' => array('Template not found!',
				'Verify if you have required theme, and if <code>'.SUMO_PATH.'/themes/<theme_name>/desktop.tpl" exist and is readable.'),
	'E00005S' => array('PHP version check failed!',
				'Sorry, this version of PHP ('.PHP_VERSION.') is not fully supported. You need 5.0 or above.', true),
	'E00006S' => array('Missing GD extension!',
				'The GD extension for PHP are missing, sorry but I cannot continue.', true),
	'E00007S' => array('Service denied!',
				'You not have permission to access this service, or you have accessed this service improperly!', true),
	//'E00008S' => array('Configuration error!',
	//				   'Node management don&#39;t work with SQLite database, please set <code>SUMO_NODES_MANAGEMENT</code> to <code>false</code> on '.SUMO_PATH.'/configs/config.server.php!'),
	'E00009S' => array('Configuration error!',
				'Sessions store to database don&#39;t work with SQLite, please set <code>SUMO_SESSIONS_DATABASE</code> to <code>false</code> on '.SUMO_PATH.'/configs/config.server.php!', true),
	'E00010S' => array('Configuration error!',
				'Sessions replica don&#39;t work with SQLite database, please set <code>SUMO_SESSIONS_REPLICA</code> to <code>false</code> on '.SUMO_PATH.'/configs/config.server.php!', true)
	/*,
	'E00011S' => array('Configuration error!',
    				   'Cannot load package configuration file on '.SUMO_PATH.'/configs/config.sumo.xml! Please make sure file exist and you have permission to read it.')
	*/
    );

    
$error = in_array($err, array_keys($errors)) ? $errors[$err] : $errors['E00000S'];


if(SUMO_VERBOSE_ERRORS || $error[2])
{
	$note = "This error page might contain sensitive information because SUMO is configured to show verbose error messages "
	       ."using <code>SUMO_VERBOSE_ERRORS=TRUE</code>. Consider using <code>SUMO_VERBOSE_ERRORS=FALSE</code> in production environments. See the file configs/config.server.php"
	       ."<br><br>\n<i>SUMO Access Manager<br>&copy; Copyright 2003-".date("Y")." by Basso Alberto</i>";

	$message = $error[1];
}
else
{
	$note = "This error page not contain sensitive information because SUMO is configured to hide verbose error messages "
	       ."using <code>SUMO_VERBOSE_ERRORS=FALSE</code>. Consider using <code>SUMO_VERBOSE_ERRORS=TRUE</code> in testing environments. See the file configs/config.server.php"
	       ."<br><br>\n<i>SUMO Access Manager<br>&copy; Copyright 2003-".date("Y")." by Basso Alberto</i>";

	$message = '';
}


/**
 *  Display startup error
 */
echo "<html>\n"
   . "<head>\n"
   . " <title>SUMO ERROR</title>\n"
   . "<style>\n"
   . "BODY  { background-color: #FFFFFF; margin: 10px; padding: 10px; }\n"
   . "TABLE { background-color: #FFFFDD; width: 100%; border: 2px solid #BB5555; padding: 10px; color: #000000; "
   . "font-family: \"Trebuchet MS\", Tahoma, Arial, Helvetica, sans-serif; font-size: 13px; }\n"
   . "H1 { font-weight:normal;font-size:18pt;color:maroon }\n"
   . "H2 { font-weight:normal;font-size:14pt;color:red }\n"
   . ".note { font-size:12px;color:#444444; }\n"
   . "</style>\n"
   . "</head>\n"
   . "<body>\n"
   . "<table>\n"
   . " <tr><td>"
   . "<h1>SUMO ERROR</h1>\n"
   . "<hr width='100%' size='1' color='#CCAABB'>"
   . "\n<h2>". $error[0] . "</h2>\n"
   . "\n<h4> code: <a href='http://sumoam.sourceforge.net/?page=documentation&subpage=startup_errors_guide#".$err."' target='_new'>".$err."</a></h4>\n"
   . "\n". $message . "\n<br>"
   . "<hr width='100%' size='1' color='#CCAABB'>"
   . "\n<font class='note'><b>Note</b>: ".$note."</font>"
   . "</td></tr>\n"
   . "</table>\n"
   . "</body>\n"
   . "</html>\n";

   exit;

?>