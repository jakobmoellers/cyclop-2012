<?php
/**
 * SUMO MODULE: Settings | View
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_user_info($_GET['id'], 'id', FALSE);		
	
$conf = sumo_get_config('server', FALSE); 
$conf = $conf['config'];

$yes = "<img src='themes/".$SUMO['page']['theme']."/images/modules/settings/yes.gif' alt='".$language['Enabled']."'>";
$no  = "<img src='themes/".$SUMO['page']['theme']."/images/modules/settings/no.gif' alt='".$language['Disabled']."'>";

// Generic       
$tpl['GET:server.admin.name']   = $conf['server']['admin']['name'];
$tpl['GET:server.admin.email']  = $conf['server']['admin']['email'];
$tpl['GET:server.language']     = ucwords(sumo_get_string_languages($conf['server']['language'])); 
$tpl['IMG:server.language'] 	= "<img src='themes/".$SUMO['page']['theme']."/images/flags/".$conf['server']['language'].".png' alt='".$tpl['GET:server.language']."' class='flag'>";
$tpl['GET:server.date_format']  = $conf['server']['date_format']; 
$tpl['GET:server.time_format']  = $conf['server']['time_format'];
$tpl['GET:iptocountry.enabled'] = $conf['iptocountry']['enabled'] ? $yes : $no;

// Console
$tpl['GET:console.tip'] = $conf['console']['tip'] ? $yes : $no;
       
// Security
$tpl['GET:security.max_login_attempts'] = $conf['security']['max_login_attempts'];
$tpl['GET:security.banned_time']        = sumo_convert_sec2hms($conf['security']['banned_time']);
$tpl['GET:security.access_violations']  = $conf['security']['access_violations'] ? $yes : $no;         

// Accesspoints
$tpl['GET:accesspoints.stats.enabled'] = $conf['accesspoints']['stats']['enabled'] ? $yes : $no;
$tpl['GET:accesspoints.def_name']      = sumo_get_accesspoint_name($conf['accesspoints']['def_name'], $_COOKIE['language']);
$tpl['GET:accesspoints.def_group']     = $conf['accesspoints']['def_group'];
$tpl['GET:accesspoints.def_theme']     = ucwords($conf['accesspoints']['def_theme']);
       
// Accounts
$tpl['GET:accounts.life'] 		       = $conf['accounts']['life']; 
$tpl['GET:accounts.registration.enabled']      = $conf['accounts']['registration']['enabled'] ? $yes : $no;
$tpl['GET:accounts.registration.life']         = $conf['accounts']['registration']['life']; 
$tpl['GET:accounts.registration.notify.reg']   = $conf['accounts']['registration']['notify']['reg']   ? $yes : $no;
$tpl['GET:accounts.registration.notify.unreg'] = $conf['accounts']['registration']['notify']['unreg'] ? $yes : $no;
$tpl['GET:accounts.password.life']             = $conf['accounts']['password']['life'];
$tpl['GET:accounts.notify.updates'] 	       = $conf['accounts']['notify']['updates'] ? $yes : $no;
$tpl['GET:accounts.notify.status'] 	       = $conf['accounts']['notify']['status']  ? $yes : $no;
$tpl['GET:accounts.notify.expired'] 	       = $conf['accounts']['notify']['expired'] ? $yes : $no;

// Log Manager
$tpl['GET:logs.life'] = "<input type='text' size='5' name='logs[life]' value='".$conf['logs']['life']."' >";
// Log Manager: System
$tpl['GET:logs.system.database.enabled'] = $conf['logs']['system']['database']['enabled'] ? $yes : $no;
$tpl['GET:logs.system.database.life']    = $conf['logs']['system']['database']['life'];
$tpl['GET:logs.system.file.enabled'] 	 = $conf['logs']['system']['file']['enabled'] ? $yes : $no;
$tpl['GET:logs.system.file.life']    	 = $conf['logs']['system']['file']['life'];
$tpl['GET:logs.system.file.size']    	 = $conf['logs']['system']['file']['size'];
$tpl['GET:logs.system.email.enabled'] 	 = $conf['logs']['system']['email']['enabled'] ? $yes : $no;
// Log Manager: Errors
$tpl['GET:logs.errors.database.enabled'] = $conf['logs']['errors']['database']['enabled'] ? $yes : $no;
$tpl['GET:logs.errors.database.life']    = $conf['logs']['errors']['database']['life'];
$tpl['GET:logs.errors.file.enabled'] 	 = $conf['logs']['errors']['file']['enabled'] ? $yes : $no;
$tpl['GET:logs.errors.file.life']    	 = $conf['logs']['errors']['file']['life'];
$tpl['GET:logs.errors.file.size']    	 = $conf['logs']['errors']['file']['size'];
$tpl['GET:logs.errors.email.enabled'] 	 = $conf['logs']['errors']['email']['enabled'] ? $yes : $no;
// Log Manager: Access
$tpl['GET:logs.access.database.enabled'] = $conf['logs']['access']['database']['enabled'] ? $yes : $no;
$tpl['GET:logs.access.database.life']    = $conf['logs']['access']['database']['life'];
$tpl['GET:logs.access.file.enabled'] 	 = $conf['logs']['access']['file']['enabled'] ? $yes : $no;
$tpl['GET:logs.access.file.life']    	 = $conf['logs']['access']['file']['life'];
$tpl['GET:logs.access.file.size']    	 = $conf['logs']['access']['file']['size'];
$tpl['GET:logs.access.email.enabled'] 	 = $conf['logs']['access']['email']['enabled'] ? $yes : $no;
      
// Sessions & Connections
$tpl['GET:sessions.timeout']     = sumo_convert_sec2hms($conf['sessions']['timeout']);
$tpl['GET:connections.timeout']  = sumo_convert_sec2hms($conf['connections']['timeout']);
$tpl['GET:sessions.auto_regenerate_id'] = $conf['sessions']['auto_regenerate_id'] ? $yes : $no;

// Database
$tpl['GET:database.optimize_hits'] = $conf['database']['optimize_hits'];
$tpl['GET:database.optimize_hits.counter'] = @file_get_contents(SUMO_PATH.'/tmp/hits/hits.'.$conf['database']['optimize_hits']);


$tpl['LINK:EditSettings'] 	= sumo_get_action_icon("settings", "edit", "settings.content", "?module=settings&action=edit&decoration=false");  
$tpl['LINK:GenericOptions']     = sumo_get_action_link('settings.view', 'GenericOptions');
$tpl['LINK:ConsoleOptions']     = sumo_get_action_link('settings.view', 'ConsoleOptions');
$tpl['LINK:SecurityOptions']    = sumo_get_action_link('settings.view', 'SecurityOptions');
$tpl['LINK:SessionsOptions']    = sumo_get_action_link('settings.view', 'SessionsOptions');
$tpl['LINK:AccountsOptions']    = sumo_get_action_link('settings.view', 'AccountsOptions');
$tpl['LINK:LoggingOptions']     = sumo_get_action_link('settings.view', 'LoggingOptions');        
$tpl['LINK:AccessPointOptions'] = sumo_get_action_link('settings.view', 'AccessPointOptions');

?>