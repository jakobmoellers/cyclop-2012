<?php
/**
 * SERVICE: Network
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

if(preg_match("/".basename(__FILE__)."/", $_SERVER['SCRIPT_NAME'])) $err = 'E00001S';

// Display startup error then exit
if($err) require SUMO_PATH.'/inc/inc.startup_errors.php';


// Get some server informations					
$SUMO['server'] = sumo_get_server_info();


switch ($_GET['cmd']) 
{	
	// Get node status
	case 'GET_NODE_STATUS':
		$status = sumo_verify_node() ? "I00013X" : "W00049X";
		
		echo $status;		
		break;
	
	case 'CREATE_SID':
		if(SUMO_SESSIONS_REPLICA)
		{
			$session = sumo_get_session_info($_GET['id']);
			
			setcookie('SUMO', $session['session_id'], null, '/');
		}
		break;
		
	// Get datasource status
	case 'GET_DS_STATUS':
		
		$id = intval($_GET['id']);
		
		if($id < 2) exit("E09000X");
		
		$available_ds = sumo_get_available_datasources();
		
		switch ($_GET['type']) 
		{
		    case 'LDAP':       $ds = 'ldap';      break;
		    case 'LDAPS':      $ds = 'ldaps';     break;
		    case 'ADAM':       $ds = 'adam';      break;
		    case 'MySQL':      $ds = 'mysql';     break;
		    case 'MySQLUsers': $ds = 'mysql_users'; break;
		    case 'Postgres':   $ds = 'postgres';  break;
		    case 'Oracle':     $ds = 'oracle';    break;
		    case 'GMail':      $ds = 'gmail';     break;
		    case 'Joomla15':   $ds = 'joomla15';  break;
		    default:           exit("W09002X");   break;
		}
		
		$ds_file = SUMO_PATH.'/libs/lib.datasource.'.$ds.'.php';
		
		if(sumo_verify_file($ds_file)) require $ds_file;
				
		$status = $sumo_verify_datasource_connection($id) ? 1 : 0;
		
		echo $status;
		
		break;
		
	// Unknow command	
	default:		
		echo "E00121X";
		break;
}

?>