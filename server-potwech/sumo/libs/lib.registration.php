<?php
/**
 * SUMO REGISTRATION FUNCTIONS LIBRARY
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */


/**
 * ADD registration request (set sumo_action=1)
 * and wait user confirmation
 */
function sumo_request_register() 
{	
	GLOBAL $SUMO, $sumo_reg_data, $sumo_lang_login;
	
	$sumo_reg_data['reg_code'] = sumo_get_simple_rand_string(40);
	
	if(!$sumo_reg_data['reg_language']) $sumo_reg_data['reg_language'] = $SUMO['config']['server']['language'];
	
	sumo_delete_user_temp(); // Delete old temp users
	
	$query = "INSERT INTO ".SUMO_TABLE_USERS_TEMP." 
			  (username, action, email, language, password, reg_group, reg_code, time) 
			  VALUES ('".$sumo_reg_data['reg_user']."', 1, 
			  '".$sumo_reg_data['reg_email']."', 
			  '".$sumo_reg_data['reg_language']."', 
			  '".$_SESSION['reg_password']."', 
              '".$SUMO['page']['reg_group']."', 
			  '".$sumo_reg_data['reg_code']."',
			   ".$SUMO['server']['time'].")";
	
	$SUMO['DB']->Execute($query);
		
	$link = $_SERVER['HTTPS'] ? 'https://' : 'http://';
	
	$link .= $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]
			.'?sumo_action=activateaccount&reg_code='.$sumo_reg_data['reg_code'];
		
	$name = sumo_get_accesspoint_name($SUMO['page']['name'], $_COOKIE['language']);
	
	$message = sumo_get_message('I00100M', 
						  array($sumo_reg_data['reg_user'],
					  		$sumo_reg_data['reg_email'],
					  		date($SUMO['config']['server']['date_format']." ".$SUMO['config']['server']['time_format'], $SUMO['server']['time']),
							"\"".$name."\"",
							$link,
							intval($SUMO['config']['accounts']['registration']['life'])
							)
				);								  	
	// Send e-mail
	if(!$SUMO['config']['server']['admin']['email']) $SUMO['config']['server']['admin']['email'] = 'sumo@localhost.com';
	
	if(!$SUMO['config']['server']['admin']['email'])
	{
		sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
	}
	else {
		$m = new Mail; 
		$m->From($SUMO['config']['server']['admin']['email']);
		$m->To($sumo_reg_data['reg_email']);		
		$m->Subject($sumo_lang_login['RegistrationObject']);        
		$m->Body($message, SUMO_CHARSET); 
		$m->Priority(3);
		$m->Send();
	}
}


/**
 * Delete user requests
 * (default all users for any request)
 * 
 * action = 0 delete account request
 * action = 1 new user account request
 * action = 2 change password request
 * action = 9 delete all requests type for user
 * 
 * @author Alberto Basso
 */
function sumo_delete_user_temp($email='', $action=NULL) 
{	
	GLOBAL $SUMO;
	
	if(sumo_validate_email($email)) 
	{		
		if($action < 9) 
			$query = "DELETE FROM ".SUMO_TABLE_USERS_TEMP." 
					  WHERE email='".$email."' 
					  AND action=".intval($action);
		else
			$query = "DELETE FROM ".SUMO_TABLE_USERS_TEMP." 
					  WHERE email='".$email."'";
	}
	else 
	{
		// Delete after $SUMO['config']['accounts']['registration']['life'] 
		// users cannot confirmed registration
		$reg_time = $SUMO['server']['time']-($SUMO['config']['accounts']['registration']['life'] * 3600);
	
		if($reg_time < $SUMO['server']['time']-3600) $reg_time = $SUMO['server']['time']-3600;
		
		$query = "DELETE FROM ".SUMO_TABLE_USERS_TEMP." 
				  WHERE time < ".$reg_time;	
	}
	
	$SUMO['DB']->Execute($query);
}


/**
 * ADD Unregister demand (set action=0) and wait user confirm
 * 
 * @author Alberto Basso
 */
function sumo_request_unregister() 
{	
	GLOBAL $SUMO, $sumo_reg_data, $sumo_lang_login;
		
	$sumo_reg_data['reg_code'] = sumo_get_simple_rand_string(40);
	
	sumo_delete_user_temp(); // Delete old temp users
	sumo_delete_user_temp($sumo_reg_data['reg_email'], 9); // delete all previous requests
		
	$user = sumo_get_user_info($sumo_reg_data['reg_email'], 'email');
			
	$query = "INSERT INTO ".SUMO_TABLE_USERS_TEMP." 
			  (username, action, email, language, password, reg_group, reg_code, time)
			  VALUES (
			  '".$user['user']."', 0, 
			  '".$user['email']."', 
			  '".$user['language']."', 
			  '".$user['password']."', 
              '".$SUMO['page']['reg_group']."', 
			  '".$sumo_reg_data['reg_code']."',
			   ".$SUMO['server']['time']."
			   )";
		
	$SUMO['DB']->Execute($query);
	
	$link = $_SERVER['HTTPS'] ? 'https://' : 'http://';
	
	$link .= $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]
			.'?sumo_action=eraseaccount&reg_code='.$sumo_reg_data['reg_code'];
	
	$name = sumo_get_accesspoint_name($SUMO['page']['name'], $_COOKIE['language']);
			
	$message = sumo_get_message('I00101M', 
					  array($user['user'],
				  		$sumo_reg_data['reg_email'],
				  		date($SUMO['config']['server']['date_format']." ".$SUMO['config']['server']['time_format'], $SUMO['server']['time']),
						"\"".$name."\"",
						$link,
						intval($SUMO['config']['accounts']['registration']['life']))
				);
	// Send e-mail
	if(!$SUMO['config']['server']['admin']['email'])
	{
		sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
	}
	else {
		$m = new Mail;
		$m->From($SUMO['config']['server']['admin']['email']);
		$m->To($sumo_reg_data['reg_email']);		
		$m->Subject($sumo_lang_login['UnRegistrationObject']);        
		$m->Body($message, SUMO_CHARSET);
		$m->Priority(3);
		$m->Send();
	}
}


/**
 * ADD registration request (set action=1) and wait user confirm
 * 
 * @author Alberto Basso
 */
function sumo_request_pwdlost() 
{	
	GLOBAL $SUMO, $sumo_lang_login, $sumo_reg_data;
	
	$new_pwd  = sumo_get_rand_string(8);
	$reg_code = sumo_get_simple_rand_string(40);	
	$user     = sumo_get_user_info($sumo_reg_data['reg_email'], 'email');
		
	sumo_delete_user_temp(); // Delete old temp users
	sumo_delete_user_temp($sumo_reg_data['reg_email'], 9); // delete all previous requests
		
	$query = "INSERT INTO ".SUMO_TABLE_USERS_TEMP." 
			  (username, action, email, language, password, reg_group, reg_code, time)
			  VALUES (
				  '".$user['user']."', 2, 
				  '".$sumo_reg_data['reg_email']."', 
				  '".$user['language']."', 
				  '".sha1($new_pwd)."', 
	              '".$SUMO['page']['reg_group']."', 
				  '".$reg_code."',
				   ".$SUMO['server']['time']."
				   )";
	
	$SUMO['DB']->Execute($query);
	
	$link = $_SERVER['HTTPS'] ? 'https://' : 'http://';
	
	$link .= $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'?sumo_action=changepwd&reg_code='.$reg_code;
	
	$name = sumo_get_accesspoint_name($SUMO['page']['name'], $_COOKIE['language']);
	
	$message = sumo_get_message('I00104M', array($user['user'], 
						  $sumo_reg_data['reg_email'],
						  date($SUMO['config']['server']['date_format']." ".$SUMO['config']['server']['time_format'], $SUMO['server']['time']),
						  "\"".$name."\"",
						  intval($SUMO['config']['accounts']['registration']['life']),
						  $link,
						  $new_pwd));		
	// Send e-mail to confirm
	if(!$SUMO['config']['server']['admin']['email'])
	{
		sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
	}
	else {
		$m = new Mail; 
		$m->From($SUMO['config']['server']['admin']['email']);
		$m->To($sumo_reg_data['reg_email']);	
		$m->Subject($sumo_lang_login['PasswordLost']);        
		$m->Body($message, SUMO_CHARSET); 
		$m->Priority(3);
		$m->Send();
	}
}


/**
 * Delete after 24h the user that cannot confirmed registration
 * 
 * @author Alberto Basso
 */
function sumo_validate_reg_code($code='') 
{	
	if(preg_match('/^[a-z0-9]{40}$/i', $code)) 
	{
		GLOBAL $SUMO;
			
		$query = "SELECT reg_code FROM ".SUMO_TABLE_USERS_TEMP." 
				  WHERE reg_code='".$code."'";
				
		$rs = $SUMO['DB']->Execute($query);
			
		if($rs->PO_RecordCount() == 1)
			return TRUE;
		else
			return FALSE;
	}
	else return FALSE;
}


/**
 * Return a "password lost" link if enabled
 * 
 * @author Alberto Basso
 */
function sumo_get_link_pwdlost() 
{	
	GLOBAL $SUMO, $sumo_lang_core;
	
	$link = $SUMO['page']['change_pwd'] ? "<a href='?sumo_action=pwdlost'>".$sumo_lang_core['PasswordLost']."</a>" : '';
		
	return $link;
}


/**
 * Return a registration/unregistration link if enabled
 */
function sumo_get_link_registration($action=1) 
{
	GLOBAL $SUMO, $sumo_lang_login;
	
	if($action == 1) 
	{
		$action = 'registration';
		$string = $sumo_lang_login['RegisterUser'];
	}
	else 
	{
		$action = 'unregister';
		$string = $sumo_lang_login['UnRegisterUser'];
	}
	
	$link = $SUMO['page']['registration'] ? "<a href='?sumo_action=".$action."'>".$string."</a>" : '';
	
	return $link;
}


/**
 * Enable Account
 * 
 * @author Alberto Basso
 */
function sumo_activate_reg_account($code='') 
{			
	if(preg_match('/^[a-z0-9]{40}$/i', $code)) 
	{		
		GLOBAL $SUMO;
			
		$query1 = "SELECT * FROM ".SUMO_TABLE_USERS_TEMP." 
				   WHERE reg_code='".$code."' AND action=1";
				 
		$rs  = $SUMO['DB']->Execute($query1);
		$tab = $rs->FetchRow();
				
		$query2 = "INSERT INTO ".SUMO_TABLE_USERS." 
				   (username, password, active, ip, usergroup, datasource_id,
				    last_login, day_limit, language, email, created, owner_id) 
				   VALUES (
				   		'".$tab['username']."',
				   		'".$tab['password']."',
				   		'1',
				   		'',
				   		'".$tab['reg_group'].":1',
				   		'1', 
				   		NULL,
				   		'".$SUMO['config']['accounts']['life']."', 
				   		'".$tab['language']."', 
				   		'".$tab['email']."',
				   		".$SUMO['server']['time'].",
				   		0
				   )";	
		                
		sumo_delete_user_temp($tab['email'], 1);
				
		$SUMO['DB']->Execute($query2);
					
		$logto = $SUMO['config']['accounts']['registration']['notify']['reg'] ? 3 : '0,1';
				
		sumo_write_log('I00002X', array($tab['username'],
								$tab['email'], 
								$tab['reg_group'],
								$SUMO['config']['accounts']['life']),
								$logto, 2);
	}	
}


/**
 * Enable new password
 * 
 * @author Alberto Basso
 */
function sumo_activate_new_password($code='') 
{			
	if(preg_match('/^[a-z0-9]{40}$/i', $code)) 
	{		
		GLOBAL $SUMO;
			
		$query1 = "SELECT * FROM ".SUMO_TABLE_USERS_TEMP." 
				   WHERE reg_code='".$code."' 
				   AND action=2";
				 
		$rs  = $SUMO['DB']->Execute($query1);
		$tab = $rs->FetchRow();
						
		$query2 = "UPDATE ".SUMO_TABLE_USERS." 
				   SET password='".$tab['password']."' 
				   WHERE username='".$tab['username']."' 
				   AND username<>'sumo'";
		
		sumo_delete_user_temp(); // Delete old temp users
		sumo_delete_user_temp($tab['email'], 2);
				
		$SUMO['DB']->Execute($query2);		
						
		// Send notify e-mail to user
		if(!$SUMO['config']['server']['admin']['email'])
		{
			sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
		}
		else {
			$name = sumo_get_accesspoint_name($SUMO['page']['name'], $_COOKIE['language']);
			
			$m = new Mail;
			$m->From($SUMO['config']['server']['admin']['email']);
			$m->To($tab['email']);		
			$m->Subject(sumo_get_message('I00012C'));        
			$m->Body(sumo_get_message("I00105M", array($tab['username'],
									date($SUMO['config']['server']['date_format']." ".$SUMO['config']['server']['time_format'], $SUMO['server']['time']),
									"\"".$name."\"")), SUMO_CHARSET);
			$m->Priority(3);
			$m->Send();
		}
		
		$logto = $SUMO['config']['accounts']['registration']['notify']['reg'] ? 3 : '0,1';
		
		sumo_write_log('I00004X', array($tab['username'], $tab['email']), $logto, 2);
	}	
}


/**
 * Erase Account
 * 
 * @author Alberto Basso
 */
function sumo_delete_account($reg_code='') 
{	
	GLOBAL $SUMO, $sumo_reg_data;
		
	if(!$reg_code) $reg_code = $sumo_reg_data['reg_code'];
	
	$query1 = "SELECT * FROM ".SUMO_TABLE_USERS_TEMP." 
			   WHERE reg_code='".$reg_code."' 
			   AND action=0";
			 
	$rs  = $SUMO['DB']->Execute($query1);
	$tab = $rs->FetchRow();
	 
	$query2 = "DELETE FROM ".SUMO_TABLE_USERS." 
			   WHERE email='".$tab['email']."' 
			   AND username='".$tab['username']."'
			   AND username<>'sumo'";
			 
	$query3 = "DELETE FROM ".SUMO_TABLE_USERS_TEMP." 
			   WHERE email='".$tab['email']."' 
			   AND username='".$tab['username']."' 
			   AND reg_code='".$reg_code."' 
			   AND action=0";
	
	$SUMO['DB']->Execute($query2);
	$SUMO['DB']->Execute($query3);
		
	// Send e-mail
	if(!$SUMO['config']['server']['admin']['email'])
	{
		sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
	}
	else {
		$m = new Mail;
		$m->From($SUMO['config']['server']['admin']['email']);
		$m->To($tab['email']);		
		$m->Subject(sumo_get_message('I00010C'));        
		$m->Body(sumo_get_message("I00102M", $tab['username'], $tab['username']), SUMO_CHARSET);
		$m->Priority(3);
		$m->Send(); 
	}
	
	$logto = $SUMO['config']['accounts']['registration']['notify']['reg'] ? 3 : '0,1';
		
	sumo_write_log('I104', array($tab['username'],$tab['email']), $logto, 2);
}

?>