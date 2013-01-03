<?php
/**
 * SERVICE: Messages
 *
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 *
 */

/**
 * Availables commands:
 *
 * GET_ERRORS_MESSAGES
 * GET_BANNED_USERS
 * GET_SQLITE_ERROR
 * GET_PASSWORD_UNCHANGED
 * GET_INSTALL_DIR_EXIST
 * GET_EXAMPLES_DIR_EXIST
 * GET_IP2COUNTRY
 * GET_USERS_LOGIN
 *
 */

// Verify startup errors
$err = FALSE;

if(preg_match("/".basename(__FILE__)."/", $_SERVER['SCRIPT_NAME'])) $err = 'E00001S'; // Can't access this file directly!

// Display startup error then exit
if ($err) require SUMO_PATH.'/inc/inc.startup_errors.php';

$m 		 = intval($_GET['m']);
$message = "";

$tpl['MESSAGE:F'] = '<form>';
$tpl['BUTTON:1'] = '';
$tpl['BUTTON:2'] = '';
$tpl['BUTTON:3'] = '';


switch ($_GET['cmd'])
{	
	case 'GET_ERRORS_MESSAGES':

		/**
		 * Log manager: errors messages
		 */
		$query = "SELECT COUNT(id) FROM ".SUMO_TABLE_LOG_ERRORS."
				  WHERE time >= ".($SUMO['server']['time']-600);

		$rs = $SUMO['DB']->Execute($query);

		$tab = $rs->FetchRow();

		if($tab[0] > 0)
		{
			$message = sumo_get_message('ErrorsMessages', $tab[0]);
		
			$l = 'h';	
			$tpl['MESSAGE:A'] = 0;
			$tpl['BUTTON:2'] = '<input type=\'button\' class="button" value="'.$language['Ok'].'" '
			  	   			   .'onclick="javascript:sumo_ajax_get(\'security\',\'?module=security&action=errors_list\');sumo_remove_window(\'msg'.$m.'\');">';
		}

		break;

	case 'GET_SQLITE_ERROR':

		/**
		 * Get SQLite errors settings
		 */
		if($SUMO['server']['db_type'] == 'sqlite')
		{
			include SUMO_PATH.'/configs/config.database.php';

			if(($sumo_db['name'] == 'sumo' || $sumo_db['name'] == 'database_sqlite.db') && ereg(SUMO_PATH, $sumo_db['path']))
			{
				$message = sumo_get_message('SQLiteError');
				
				$l = 'h';	
				$tpl['MESSAGE:A'] = 1;
			}
		}

		break;

	case 'GET_INSTALL_DIR_EXIST':

		/**
		 * Remove installation directory for security reason and to save space
		 */
		if(file_exists(SUMO_PATH.'/.installed') && file_exists(SUMO_PATH.'/install/'))
		{	
			$message = sumo_get_message('RemoveInstallDir');
				
			$l = 'm';	
			$tpl['MESSAGE:A'] = 1;
		}

		break;

	case 'GET_EXAMPLES_DIR_EXIST':

		/**
		 * Remove examples directory for security reason
		 */
		if(file_exists(SUMO_PATH.'/examples/'))
		{			
			$message = sumo_get_message('RemoveExamplesDir');
				
			$l = 'm';	
			$tpl['MESSAGE:A'] = 1;
		}

		break;

	/**
	 * Get banned
	 */
	case 'GET_BANNED_USERS':

		$query = "SELECT COUNT(id) FROM ".SUMO_TABLE_BANNED;

		$rs = $SUMO['DB']->Execute($query);

		$tab = $rs->FetchRow();

		if($tab[0] > 0)
		{		
			$message = sumo_get_message('BannedUsers', $tab[0]);
				
			$l = 'h';	
			$tpl['MESSAGE:A'] = 0;
			$tpl['BUTTON:2'] = '<input type="button" class="button" value="'.$language['Ok'].'" '
							   .'onclick="javascript:sumo_ajax_get(\'security\',\'?module=security&action=banned\');'
							   .'sumo_remove_window(\'msg'.$m.'\');">';
		}

		break;


	case 'GET_PASSWORD_UNCHANGED':

		// Verify if sumo user have changed the password
		$query = "SELECT id,username,password FROM ".SUMO_TABLE_USERS."
				  WHERE id=".intval($_GET['id'])."
				  AND datasource_id = (
				  		SELECT id FROM ".SUMO_TABLE_DATASOURCES."
						WHERE type='SUMO'
		 		  )";

		$rs = $SUMO['DB']->Execute($query);

		$tab = $rs->FetchRow();

		if($tab['password'] == sha1($tab['username']))
		{
			$message = sumo_get_message('ChangePassword');
				
			$l = 'h';	
			$tpl['MESSAGE:A'] = 0;
			$tpl['BUTTON:1'] = '<input type=\'button\' class=\'button\' value=\''.$language['Close'].'\' '
							  .'onclick="javascript:sumo_remove_window(\'msg'.$m.'\');">';
			$tpl['BUTTON:2'] = '<input type=\'button\' class=\'button\' value=\''.$language['ChangeNow'].'\' '
							  .'onclick="javascript:sumo_ajax_get(\'users\',\'?module=users&action=edit_password\');'
							  .'sumo_remove_window(\'msg'.$m.'\');">';
		}

		break;


	case 'GET_IP2COUNTRY':

		/**
		 * IP2Country enabled but empty
		 */
		if($SUMO['config']['iptocountry']['enabled'])
		{
			$query = "SELECT COUNT(*) FROM ".SUMO_TABLE_IPTOCOUNTRY;

			$rs = $SUMO['DB']->Execute($query);

			$tab = $rs->FetchRow();

			if($tab[0] == 0)
			{
				$message = sumo_get_message('IP2CountryEmpty');
					
				$l = 'm';	
				$tpl['MESSAGE:A'] = 1;
				$tpl['BUTTON:2'] = '<input type="button" class="button" value="'.$language['Ok'].'" '
								  .'onclick="javascript:sumo_ajax_get(\'settings\',\'?module=settings&action=edit\');sumo_remove_window(\'msg'.$m.'\');">';
			}
			else 
			{
				touch(SUMO_PATH.'/tmp/iptocountry');
			}
		}

		break;


	//case 'GET_SESSIONS_EXPIRING':
		/**
		 * Get session expiring
		 *
		$query = "SELECT id FROM ".SUMO_TABLE_SESSIONS."
				  WHERE id_user=".intval($_GET['id'])."
				  	AND session_id='".$_SESSION['id']."'
				  	AND expire <= ".($SUMO['server']['time']+300);

		$rs = $SUMO['DB']->Execute($query);

		$ex = $rs->PO_RecordCount();

		if($ex > 0)
		{
			$message[$m] = "sumo_ajax_get(\"messages$m\",\"?module=messages&m=$m&cmd=GET_MESSAGES_MEDIUM&decoration=false&msg=SessionExpiring\")"
						  .";setTimeout('opacity(\"messages$m\", 100, 0, 500)',6000)"
						  .";setTimeout('sumo_remove_window(\"messages$m\")',7000)";
			$m++;
		}


		break;
		*/

	case 'GET_USERS_LOGIN':

		/**
		 * Get users login
		 */
		$query = "SELECT DISTINCT(username) FROM ".SUMO_TABLE_SESSIONS."
				  WHERE connected >= ".($SUMO['server']['time']-60);

		$rs = $SUMO['DB']->Execute($query);

		$users = array();

		while($tab = $rs->FetchRow())
		{
			$users[] = $tab['username'];
		}
		
		if(!empty($users))
		{			  
			$message = sumo_get_message('UserLogin', implode(", ", $users));
					
			$l = 'l';	
			$tpl['MESSAGE:A'] = 1;
		}

		break;


	case 'GET_USERS_LOGOUT':

		/**
		 * Get users logout
		 */
		$query = "SELECT message FROM ".SUMO_TABLE_LOG_ACCESS."
				  WHERE code='I00201X'
				  	AND	time >= ".($SUMO['server']['time']-65);

		$rs = $SUMO['DB']->Execute($query);

		$users = array();

		while($tab = $rs->FetchRow())
		{
			// Very BAD solution (for now)!!!
			$message = explode(" ", $tab['message']);
			$users[] = $message[1];
		}

		if(!empty($users))
		{
			$message = sumo_get_message('UserLogout', implode(", ", $users));
					
			$l = 'l';	
			$tpl['MESSAGE:A'] = 1;
		}

		break;


	// Unknow command
	default:
		echo "E00121X";
		break;
}

// Create message
if($message != "")
{
	echo "<SCRIPT>sumo_show_message('msg$m', '$message', '$l', 
									'{$tpl['MESSAGE:A']}',
									'".base64_encode($tpl['MESSAGE:F'])."',
									'".base64_encode($tpl['BUTTON:1'])."',
									'".base64_encode($tpl['BUTTON:2'])."',
									'".base64_encode($tpl['BUTTON:3'])."');";
}

?>