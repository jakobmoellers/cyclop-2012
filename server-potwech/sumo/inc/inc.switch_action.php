<?php

$sumo_template = 'message';
$sumo_message  = '';
$update_req    = FALSE;


switch($sumo_access) 
{	
	case 'IPDISABLED':
		$sumo_message = sumo_get_message('E00101C', $SUMO['client']['ip']);
		
		sumo_write_log('E00118X', array($SUMO['client']['ip'], 
									$SUMO['client']['country'], 
									$SUMO['page']['url'],
									sumo_get_accesspoint_name($SUMO['page']['name'], $SUMO['config']['server']['language'])), 
									3, 1, 'errors');		
		session_destroy();
		break;
	
	case 'NODEDISABLED':
		$sumo_message = sumo_get_message('E00120C');
		
		sumo_write_log('E00120X', array($SUMO['server']['name'],
						$SUMO['page']['url'],
						sumo_get_accesspoint_name($SUMO['page']['name'], $SUMO['config']['server']['language'])), 
					3, 1, 'errors');
									 	
		// Prevent destroy valid session
		// on others nodes if sessions replica is enabled
		if(!SUMO_SESSIONS_REPLICA) session_destroy();
		break;	
		
	case 'USERNOTEXIST':
		$update_req   = TRUE;
		$sumo_message = sumo_get_message('W00001C', $_SESSION['user']['user']);
						
		sumo_write_log('W00042X', array($_SESSION['user']['user'], 
						$SUMO['client']['ip'], 
						$SUMO['client']['country'], 
						$SUMO['page']['url'],
						sumo_get_accesspoint_name($SUMO['page']['name'], $SUMO['config']['server']['language'])), 
					'0,1', 2, 'errors');		
		session_destroy();		
		break;
		
	case 'USERNOTACTIVE':
		$update_req   = TRUE;
		$sumo_message = sumo_get_message('W00002C', $SUMO['client']['user']);
		
		sumo_write_log('W00043X', array($SUMO['user']['user'], 
						$SUMO['page']['url'],
						sumo_get_accesspoint_name($SUMO['page']['name'], $SUMO['config']['server']['language'])), 
					'0,1', 2, 'errors');
		session_destroy();
		break;
		
	case 'CANNOTAUTHENTICATE':
		$sumo_message = sumo_get_message('W00034C');
		
		sumo_write_log('W00100X', array($SUMO['page']['url'],
						$SUMO['user']['user'], 
					 	$SUMO['client']['ip'],
					 	$SUMO['client']['country']),
					'0,1', 2, 'system');	
		session_destroy();
		break;
		
	case 'PASSWORDERROR':
		$update_req   = TRUE;
		$sumo_message = sumo_get_message('W00003C');
		
		sumo_write_log('W00044X', array($SUMO['user']['user'], 
						$SUMO['client']['ip'],
						$SUMO['client']['country'], 
						$SUMO['page']['url']),
					'0,1', 2, 'errors');
		session_destroy();
		break;
			
	case 'LDAPMODULEERROR':
		$sumo_message = sumo_get_message('E00119X');	
		sumo_write_log("E00119X", '', '0,1', 1);
		session_destroy();
		break;
			
	case 'LDAPCONNECTIONFAILED':
		$sumo_message = sumo_get_message('W00048C');
		$datasource   = sumo_get_datasource_info($SUMO['user']['datasource_id']);		
		sumo_write_log("W00048X", $datasource['name'], '0,1', 2);
		session_destroy();
		break;
	
	case 'MYSQLMODULEERROR':
		$sumo_message = sumo_get_message('E00122C');	
		sumo_write_log("E00122C", '', '0,1', 1);
		session_destroy();
		break;
		
	case 'GMAILCONNECTIONFAILED':
		$sumo_message = sumo_get_message('W00055C');	
		sumo_write_log("W00055C", '', '0,1', 1);
		session_destroy();
		break;
					
	case 'CURLMODULEERROR':
		$sumo_message = sumo_get_message('E00123C');	
		sumo_write_log("E00123C", '', '0,1', 1);
		session_destroy();
		break;		
			
	case 'MYSQLCONNECTIONFAILED':
		$sumo_message = sumo_get_message('W00051C');
		$datasource   = sumo_get_datasource_info($SUMO['user']['datasource_id']);
		sumo_write_log("W00051X", $datasource['name'], '0,1', 2);
		session_destroy();
		break;
	
	case 'POSTGRESCONNECTIONFAILED':
		$sumo_message = sumo_get_message('W00052C');
		$datasource   = sumo_get_datasource_info($SUMO['user']['datasource_id']);
		sumo_write_log("W00052X", $datasource['name'], '0,1', 2);
		session_destroy();
		break;
	
	case 'JOOMLACONNECTIONFAILED':
		$sumo_message = sumo_get_message('W00056C');
		$datasource   = sumo_get_datasource_info($SUMO['user']['datasource_id']);
		sumo_write_log("W00056X", $datasource['name'], '0,1', 2);
		session_destroy();
		break;
		
	case 'ORACLEMODULEERROR':
		$sumo_message = sumo_get_message('E00121C');	
		sumo_write_log("E00121C", '', '0,1', 1);
		session_destroy();
		break;
			
	case 'ORACLECONNECTIONFAILED':
		$sumo_message = sumo_get_message('W00053C');
		$datasource   = sumo_get_datasource_info($SUMO['user']['datasource_id']);
		sumo_write_log("W00053X", $datasource['name'], '0,1', 2);
		session_destroy();
		break;
		
	case 'UNIXCONNECTIONFAILED':
		$sumo_message = sumo_get_message('W00054C');
		sumo_write_log("W00054X", '', '0,1', 2);
		session_destroy();
		break;

	case 'UNDEFINEDDS':
		$sumo_message = sumo_get_message('E00125C');
		$datasource   = sumo_get_datasource_info($SUMO['user']['datasource_id']);
		sumo_write_log("E00125X", $datasource['name'], '0,1', 2);
		session_destroy();
		break;
		
	case 'IPDENIED':
		$update_req   = TRUE;
		$sumo_message = sumo_get_message('W00004C', $SUMO['client']['ip']);		
		sumo_write_log('W00045X', array($SUMO['client']['ip'],
						$SUMO['user']['user'], 
						$SUMO['client']['country'],
						$SUMO['page']['url']),
						'0,1', 2, 'errors');
		session_destroy();
		break;
		
	case 'GROUPDENIED':
		$update_req   = TRUE;
		$sumo_message = sumo_get_message('W00005C');		
		sumo_write_log('W00046X', array($SUMO['user']['user'],
						$SUMO['user']['group'],
						$SUMO['page']['url'],							 
	 					$SUMO['page']['group']),									 
						'0,1', 2, 'errors');
		session_destroy();
		break;
	
	case 'SESSIONENDED': 
		$sumo_message = sumo_get_message('I00005C');
		sumo_user_logout();			
		break;	
	
	case 'ACCOUNTEXPIRED': 
		$sumo_message = sumo_get_message('E00102C');		
		session_destroy();
		break;
		
	case 'LOGIN':
	    sumo_user_login();
		if($SUMO['config']['accesspoints']['stats']['enabled']) 
		{
			sumo_update_accesspoints_stats('access');
		}
		break;	
	
	case 'LOGINSE':  // Login after session ended
		sumo_user_logout();
		header('Location: '.$SUMO['page']['url']);
		break;
	
	case 'LOGOUT': 
		$sumo_message = sumo_get_message('I00006C');
		sumo_user_logout();	// include "session_destroy"	
		break;	
		
	case 'REGISTRATION':
		$sumo_template = 'registration';
		if(!$SUMO['config']['accounts']['registration']['enabled']) 
		{
			$sumo_template = 'message';
			$sumo_message  = sumo_get_message('W00013C');
		}
		session_destroy();
		break;
		
	case 'CONFIRMREG':
		
        if($SUMO['config']['accounts']['registration']['enabled']) 
        {
		$sumo_template = 'registration';
	    	
		$data = array(
            			  array('username',  $sumo_reg_data['reg_user'],  1),
						  array('email', $sumo_reg_data['reg_email'], 1),
						  array('new_password', array($sumo_reg_data['reg_password'], $sumo_reg_data['rep_reg_password']), 1)
                          );
		                
		    $validate = sumo_validate_data($data, TRUE);
                              
            if($validate[0]) 
            {
			    $sumo_template = 'confirm_registration';
			    $_SESSION['reg_password'] = $sumo_reg_data['reg_password'];
		    }
		    else 
		    {
                $sumo_message = $validate[1];
                session_destroy();     
            }
        }
        else 
        {
			$sumo_message = sumo_get_message('W00013C');
            session_destroy();     
        }
        
		break;
		
	case 'REGCONFIRMED':
		$sumo_template = 'registration';
            
		if($SUMO['config']['accounts']['registration']['enabled']) 
		{            
		$data = array(
            			  array('username', $sumo_reg_data['reg_user'],  1),
						array('email',    $sumo_reg_data['reg_email'], 1),
						array('password', $_SESSION['reg_password'],   1)
                          );
	
		$validate = sumo_validate_data($data, TRUE);
                              
		if($validate[0]) 
		{
			    if(sumo_verify_user_exist($sumo_reg_data['reg_user'])) 
					$sumo_message  = sumo_get_message('W00008C');
				elseif(sumo_verify_email_exist($sumo_reg_data['reg_email'])) 
					$sumo_message  = sumo_get_message('W00009C');
				else 
				{
					$sumo_message  = sumo_get_message('I00007C');
					$sumo_template = 'message';	
					sumo_request_register();
				}
		    }
		    else $sumo_message = $validate[1];
        }
        else 
        {
            $sumo_template = 'message';
			$sumo_message  = sumo_get_message('W00013C');
        }
		session_destroy();
		break;
	
	case 'UNREGISTER':		
		$sumo_template = 'unregister';		
		session_destroy();
		break;
	
	case 'CONFIRMERASEACCOUNT':				
		$sumo_template = 'unregister';
		
		if(!sumo_validate_email($sumo_reg_data['reg_email'])) 
			$sumo_message = sumo_get_message('W00007C');			
		else 
		{			
			$sumo_message  = sumo_get_message('I00009C');
			$sumo_template = 'confirm_erase_account';
		}				
		session_destroy();		
		break;	
	
	case 'ACTIVATEACCOUNT':
		if(!sumo_validate_reg_code($sumo_reg_data['reg_code'])) 
			$sumo_message = sumo_get_message('W00010C');		
		else 
		{
			sumo_activate_reg_account($sumo_reg_data['reg_code']);
			$sumo_message = sumo_get_message('I00008C');
		}		
		session_destroy();
		break;
		
	case 'ERASEACCOUNTCONFIRMED':
		if(!sumo_validate_email($sumo_reg_data['reg_email'])) 
			$sumo_message = sumo_get_message('W00007C');		
		else 
		{			
			$sumo_message = sumo_get_message('I00009C');				
			sumo_request_unregister();
		}		
		session_destroy();
		break;
	
	case 'ERASEACCOUNT':
		$update_req = TRUE;
		
		if(!sumo_validate_reg_code($sumo_reg_data['reg_code'])) 
			$sumo_message = sumo_get_message('W00012C');		
		else 
		{
			sumo_delete_account();
			$sumo_message = sumo_get_message('I00010C');
		}
		
		session_destroy();
		break;
	
	case 'PWDLOST':		
		$sumo_template = 'password_lost';
		session_destroy();
		break;
				
	case 'PWDLOSTCONFIRMED':
		if(!sumo_validate_email($sumo_reg_data['reg_email'])) 
			$sumo_message = sumo_get_message('W00007C');		
		else 
		{			
			$sumo_message = sumo_get_message('I00011C');				
			sumo_request_pwdlost();
		}		
		session_destroy();
		break;
	
	case 'CHANGEPWD':
		if($SUMO['page']['change_pwd']) 
		{
			if(!sumo_validate_reg_code($sumo_reg_data['reg_code'])) 
				$sumo_message = sumo_get_message('W00014C');		
			else 
			{
				sumo_activate_new_password($sumo_reg_data['reg_code']);
				$sumo_message = sumo_get_message('I00012C');
			}
		}
		else 
		{
			$sumo_message = sumo_get_message('W00015C');
		}
		session_destroy();
		break;
		
	case 'CONTINUE':
		$_SESSION['loggedin'] = TRUE;
				
		// don't update sess data when refresh a window
		// see: sumo_refresh_window on scripts/
		if(!$_GET['refresh']) 
		{
			/**
			 * Regenerate session id approximately every 10 page loads.
			 * NOTE: don't work if sessions replica is enabled
			 * WARNING: maybe don't work on system with heavy load !!!
			 */
			if($SUMO['config']['sessions']['auto_regenerate_id'])
			{
				if (!SUMO_SESSIONS_REPLICA && (rand()%10) == 0) sumo_session_regenerate_id();	
			}
			
			sumo_update_session_data();
			
			// Statistics
			if($SUMO['config']['accesspoints']['stats']['enabled']) 
			{
				sumo_update_accesspoints_stats('activity');			
			}
		}
		break;	
		
	case 'NULL':
		$sumo_template = 'login';
		$update_req    = TRUE;
		session_destroy();
		break;	
		
	default:
		$sumo_template = 'login';
		$sumo_message  = 'UNDEFINED ACTION';
		$update_req    = TRUE;
		session_destroy();
		break;
}

?>