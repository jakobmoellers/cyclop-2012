<?php
/**
 * SUMO SERVICES DRIVER
 *
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

// Prevent loop and server timeout for services
if(!ini_get("safe_mode")) set_time_limit(20);


// Set SUMO_PATH to the directory where this file resides...
define('SUMO_PATH', dirname(__FILE__));


// ADODB Parameters
$ADODB_CACHE_DIR = SUMO_PATH.'/tmp/database/';
$ADODB_COUNTRECS = FALSE;


// Load configuration, libraries, and classes
require 'configs/config.sumo.php';
require 'configs/config.server.php';
require 'configs/config.database.php';
require 'libs/lib.core.php';
require 'libs/lib.console.php';
require 'classes/class.sendmail.php';
require 'applications/adodb/adodb.inc.php';
require 'inc/inc.php_modules.php';
require 'inc/inc.setup.php';

// External library
if(file_exists(SUMO_PATH.'/libs/lib.console.ext.php')) require SUMO_PATH.'/libs/lib.console.ext.php';


// See also configs/config.server.php
if(SUMO_VERBOSE_ERRORS)
{
	error_reporting(E_ALL);
}
else
{
	error_reporting(0); 		  // comment this line only for debug
	ini_set('display_errors', 0); // do not echo any ADODB errors
}


// Database connection
require SUMO_PATH.'/inc/inc.db_connection.php';


// Get configuration parameters
$SUMO = array_merge(
					$SUMO,
				  	sumo_get_config('server'),
					sumo_get_config('database')
					);

$SUMO['client'] = sumo_get_client_info(); // Get client informations (except session id)
$SUMO['server'] = sumo_get_server_info(); // Get some server informations
$SUMO['page']   = sumo_get_accesspoint_info(); // Get protection parameters for requested page


$sumo_db = NULL;  // delete database connection parameters!

// Verify if node client it's enabled to call services (except for create SSO)
if(!sumo_verify_node($SUMO['client']['ip']) && $_GET['cmd'] != 'CREATE_SSO') exit('W00050X');

// Check login
//if($_GET['cmd'] != 'CREATE_SSO' && $_COOKIE['loggedin'] != 1) exit();


// Filter all input data
$_GET = sumo_array_combine(array_keys($_GET), sumo_array_filter(array_values($_GET)));

$service_file = SUMO_PATH.'/services/service.'.$_GET['service'].'.php';


if(@file_exists($service_file))
{	
	require $service_file;
}
else
{
	define('SUMO_PATH_MODULE', SUMO_PATH.'/modules/'.$_GET['module']);

	$module['file'] = array(
							'language' => SUMO_PATH_MODULE.'/languages/lang.'.$_COOKIE['language'].'.php',
							'library'  => SUMO_PATH_MODULE.'/libraries/lib.common.php',
							'module'   => SUMO_PATH_MODULE.'/module.php',
							'config'   => SUMO_PATH_MODULE.'/module.xml',
							'service'  => SUMO_PATH_MODULE.'/services/service.'.$_GET['service'].'.php'
						   );

	// Get available modules
	$modules = sumo_get_available_modules();

	// Load module library and service if exist
	if(in_array($_GET['module'], $modules))
	{
		if(@file_exists($module['file']['module']))   require $module['file']['module'];
		if(@file_exists($module['file']['language'])) require $module['file']['language'];

		// language dictionary
		$language = $module['language'];

		if(@file_exists($module['file']['library']))  require $module['file']['library'];
		if(@file_exists($module['file']['service']))  require $module['file']['service'];

	} // end load module
}

?>