<?php
/**
 * SERVICE: Profiler
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * 
 */


// Verify startup errors  
$err = FALSE;

if(preg_match("/".basename(__FILE__)."/", $_SERVER['SCRIPT_NAME'])) $err = 'E00001S'; // Can't access this file directly!

// Display startup error then exit
if ($err) require SUMO_PATH.'/inc/inc.startup_errors.php';


switch ($_GET['cmd']) 
{
	case 'SAVE_ICON_SETTINGS':
			
		sumo_save_icon_settings($_GET['user'], 
								$_GET['module'], 
								$_GET['x'], 
								$_GET['y']);
		break;
	
	case 'SAVE_WINDOW_SETTINGS':
		
		sumo_save_window_settings($_GET['user'], 
								  $_GET['module'], 
								  $_GET['x'], 
								  $_GET['y'],
								  $_GET['s']);
		break;

	// Unknow command	
	default:		
		echo "E00121X";
		break;
}

?>