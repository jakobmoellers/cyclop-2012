<?php
/**
 * SUMO MODULE: Settings | Edit
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */


$tab  = sumo_get_user_info($_GET['id'], 'id', FALSE);
$conf = sumo_get_config('server', FALSE); 
$conf = $conf['config'];

$tpl['GET:UpdateForm'] = sumo_get_form_req('', 'modify');
        
// Generic       
$tpl['PUT:server.language']     = sumo_get_available_languages(1, 0, $conf['server']['language'], 'config[server][language]');
$tpl['IMG:server.language'] 	= "<img src='themes/".$SUMO['page']['theme']."/images/flags/".$conf['server']['language'].".png' alt='".ucwords($conf['server']['language'])."' class='flag'>";
$tpl['PUT:server.date_format']  = "<input type='text' size='5' name='config[server][date_format]' value='".$conf['server']['date_format']."'>"; 
$tpl['PUT:server.time_format']  = "<input type='text' size='5' name='config[server][time_format]' value='".$conf['server']['time_format']."'>";         
$tpl['PUT:server.admin.name']   = "<input type='text' size='30' name='config[server][admin][name]' value='".$conf['server']['admin']['name']."'>";
$tpl['PUT:server.admin.email']  = "<input type='text' size='30' name='config[server][admin][email]' value='".$conf['server']['admin']['email']."'>";                
$tpl['PUT:iptocountry.enabled'] = $conf['iptocountry']['enabled'] ? "<input type='checkbox' name='config[iptocountry][enabled]' checked='checked'>" : "<input type='checkbox' name='config[iptocountry][enabled]'>";
$tpl['GET:iptocountry.updater'] = "<a href='services.php?service=updater&cmd=UPDATE_IP2C' target='_new'>".$language['iptocountry.updater']."</a>";

// Console        
$tpl['PUT:console.tip'] = $conf['console']['tip'] ? "<input type='checkbox' name='config[console][tip]' checked='checked'>" : "<input type='checkbox' name='config[console][tip]'>";

// Security
$tpl['PUT:security.max_login_attempts'] = "<input type='text' size='5' name='config[security][max_login_attempts]' value='".$conf['security']['max_login_attempts']."'>";
$tpl['PUT:security.banned_time']        = "<input type='text' size='5' name='config[security][banned_time]' value='".$conf['security']['banned_time']."'>";
$tpl['PUT:security.access_violations']  = $conf['security']['access_violations'] ? "<input type='checkbox' name='config[security][access_violations]' checked='checked'>" : "<input type='checkbox' name='config[security][access_violations]'>";

// Accesspoints
$tpl['PUT:accesspoints.stats.enabled'] = $conf['accesspoints']['stats']['enabled'] ? "<input type='checkbox' name='config[accesspoints][stats][enabled]' checked='checked'>" : "<input type='checkbox' name='config[accesspoints][stats][enabled]'>";
$tpl['PUT:accesspoints.def_name']      = sumo_put_accesspoint_name('ModifySettings', sumo_get_accesspoint_name($conf['accesspoints']['def_name']));
$tpl['PUT:accesspoints.def_group']     = sumo_put_accesspoint_group($conf['accesspoints']['def_group']);
$tpl['PUT:accesspoints.def_theme']     = sumo_put_themes($conf['accesspoints']['def_theme'], 'config[accesspoints][def_theme]');
        
// Accounts
$tpl['PUT:accounts.life'] 		       = "<input type='text' size='5' name='config[accounts][life]' value='".$conf['accounts']['life']."'>"; 
$tpl['PUT:accounts.registration.enabled']      = $conf['accounts']['registration']['enabled'] ? "<input type='checkbox' name='config[accounts][registration][enabled]' checked='checked'>" : "<input type='checkbox' name='config[accounts][registration][enabled]'>";
$tpl['PUT:accounts.registration.life']         = "<input type='text' size='5' name='config[accounts][registration][life]' value='".$conf['accounts']['registration']['life']."'>"; 
$tpl['PUT:accounts.registration.notify.reg']   = $conf['accounts']['registration']['notify']['reg'] ? "<input type='checkbox' name='config[accounts][registration][notify][reg]' checked='checked'>" : "<input type='checkbox' name='config[accounts][registration][notify][reg]'>";
$tpl['PUT:accounts.registration.notify.unreg'] = $conf['accounts']['registration']['notify']['unreg'] ? "<input type='checkbox' name='config[accounts][registration][notify][unreg]' checked='checked'>" : "<input type='checkbox' name='config[accounts][registration][notify][unreg]'>";
$tpl['PUT:accounts.password.life']             = "<input type='text' size='5' name='config[accounts][password][life]' value='".$conf['accounts']['password']['life']."'>";
$tpl['PUT:accounts.notify.updates'] 	       = $conf['accounts']['notify']['updates'] ? "<input type='checkbox' name='config[accounts][notify][updates]' checked='checked'>" : "<input type='checkbox' name='config[accounts][notify][updates]'>";
$tpl['PUT:accounts.notify.status'] 	       = $conf['accounts']['notify']['status']  ? "<input type='checkbox' name='config[accounts][notify][status]' checked='checked'>" : "<input type='checkbox' name='config[accounts][notify][status]'>";
$tpl['PUT:accounts.notify.expired'] 	       = $conf['accounts']['notify']['expired'] ? "<input type='checkbox' name='config[accounts][notify][expired]' checked='checked'>" : "<input type='checkbox' name='config[accounts][notify][expired]'>";

// Log to file format
//$tpl['PUT:logs.format'] = $conf['logs']['format'] ? $conf['logs']['format'] : "[".$SUMO['config']['server']['date_format']." ".$SUMO['config']['server']['time_format']. " O]";

// Log Manager: System
$tpl['PUT:logs.system.database.enabled'] = $conf['logs']['system']['database']['enabled'] ? "<input type='checkbox' name='config[logs][system][database][enabled]' checked='checked'>" : "<input type='checkbox' name='config[logs][system][database][enabled]'>";
$tpl['PUT:logs.system.database.life']    = "<input type='text' size='5' name='config[logs][system][database][life]' value='".$conf['logs']['system']['database']['life']."'>";
$tpl['PUT:logs.system.file.enabled'] 	 = $conf['logs']['system']['file']['enabled'] ? "<input type='checkbox' name='config[logs][system][file][enabled]' checked='checked'>" : "<input type='checkbox' name='config[logs][system][file][enabled]'>";
$tpl['PUT:logs.system.file.life']    	 = "<input type='text' size='5' name='config[logs][system][file][life]' value='".$conf['logs']['system']['file']['life']."'>";
$tpl['PUT:logs.system.file.size']    	 = "<input type='text' size='5' name='config[logs][system][file][size]' value='".$conf['logs']['system']['file']['size']."'>";
$tpl['PUT:logs.system.email.enabled'] 	 = $conf['logs']['system']['email']['enabled'] ? "<input type='checkbox' name='config[logs][system][email][enabled]' checked='checked'>" : "<input type='checkbox' name='config[logs][system][email][enabled]'>"; 
// Log Manager: Errors
$tpl['PUT:logs.errors.database.enabled'] = $conf['logs']['errors']['database']['enabled'] ? "<input type='checkbox' name='config[logs][errors][database][enabled]' checked='checked'>" : "<input type='checkbox' name='config[logs][errors][database][enabled]'>";
$tpl['PUT:logs.errors.database.life']    = "<input type='text' size='5' name='config[logs][errors][database][life]' value='".$conf['logs']['errors']['database']['life']."'>";
$tpl['PUT:logs.errors.file.enabled'] 	 = $conf['logs']['errors']['file']['enabled'] ? "<input type='checkbox' name='config[logs[errors][file][enabled]' checked='checked'>" : "<input type='checkbox' name='config[logs][errors][file][enabled]'>";
$tpl['PUT:logs.errors.file.life']    	 = "<input type='text' size='5' name='config[logs][errors][file][life]' value='".$conf['logs']['errors']['file']['life']."'>";
$tpl['PUT:logs.errors.file.size']    	 = "<input type='text' size='5' name='config[logs][errors][file][size]' value='".$conf['logs']['errors']['file']['size']."'>";
$tpl['PUT:logs.errors.email.enabled'] 	 = $conf['logs']['errors']['email']['enabled'] ? "<input type='checkbox' name='config[logs][errors][email][enabled]' checked='checked'>" : "<input type='checkbox' name='config[logs][errors][email][enabled]'>";
// Log Manager: Access
$tpl['PUT:logs.access.database.enabled'] = $conf['logs']['access']['database']['enabled'] ? "<input type='checkbox' name='config[logs][access][database][enabled]' checked='checked'>" : "<input type='checkbox' name='config[logs][access][database][enabled]'>";
$tpl['PUT:logs.access.database.life']    = "<input type='text' size='5' name='config[logs][access][database][life]' value='".$conf['logs']['access']['database']['life']."'>";
$tpl['PUT:logs.access.file.enabled'] 	 = $conf['logs']['access']['file']['enabled'] ? "<input type='checkbox' name='config[logs][access][file][enabled]' checked='checked'>" : "<input type='checkbox' name='config[logs][access][file][enabled]'>";
$tpl['PUT:logs.access.file.life']    	 = "<input type='text' size='5' name='config[logs][access][file][life]' value='".$conf['logs']['access']['file']['life']."'>";
$tpl['PUT:logs.access.file.size']    	 = "<input type='text' size='5' name='config[logs][access][file][size]' value='".$conf['logs']['access']['file']['size']."'>";
$tpl['PUT:logs.access.email.enabled'] 	 = $conf['logs']['access']['email']['enabled'] ? "<input type='checkbox' name='config[logs][access][email][enabled]' checked='checked'>" : "<input type='checkbox' name='config[logs][access][email][enabled]'>";

// Sessions & Connections
$tpl['PUT:connections.timeout'] = "<input type='text' size='5' name='config[connections][timeout]' value='".$conf['connections']['timeout']."'>";
$tpl['PUT:sessions.timeout']    = "<input type='text' size='5' name='config[sessions][timeout]' value='".$conf['sessions']['timeout']."'>";
$tpl['PUT:sessions.auto_regenerate_id'] = $conf['sessions']['auto_regenerate_id'] ? "<input type='checkbox' name='config[sessions][auto_regenerate_id]' checked='checked'>" : "<input type='checkbox' name='config[sessions][auto_regenerate_id]'>";

if(SUMO_SESSIONS_REPLICA) $tpl['PUT:sessions.auto_regenerate_id'] = "<input type='checkbox' name='config[sessions][auto_regenerate_id]' disabled>";


// Database
$tpl['PUT:database.optimize_hits'] = "<input type='text' size='5' name='config[database][optimize_hits]' value='".$conf['database']['optimize_hits']."'>";
$tpl['GET:database.optimize_hits.counter'] = @file_get_contents(SUMO_PATH.'/tmp/hits/hits.'.$conf['database']['optimize_hits']);

$tpl['LINK:EditSettings'] = sumo_get_action_icon("settings", "edit");  

// Set sub module visibility 
$visibility['GenericOptions']     = $_REQUEST['GenericOptions_visibility']     ? true : false;
$visibility['ConsoleOptions']     = $_REQUEST['ConsoleOptions_visibility']     ? true : false;
$visibility['SecurityOptions']    = $_REQUEST['SecurityOptions_visibility']    ? true : false;
$visibility['SessionsOptions']    = $_REQUEST['SessionsOptions_visibility']    ? true : false;
$visibility['AccountsOptions']    = $_REQUEST['AccountsOptions_visibility']    ? true : false;
$visibility['LoggingOptions']     = $_REQUEST['LoggingOptions_visibility']     ? true : false;
$visibility['AccessPointOptions'] = $_REQUEST['AccessPointOptions_visibility'] ? true : false;  // see action.stats.php


$tpl['LINK:GenericOptions']     = sumo_get_action_link('ModifySettings', 'GenericOptions',  $visibility['GenericOptions']);
$tpl['LINK:ConsoleOptions']     = sumo_get_action_link('ModifySettings', 'ConsoleOptions',  $visibility['ConsoleOptions']);
$tpl['LINK:SecurityOptions']    = sumo_get_action_link('ModifySettings', 'SecurityOptions', $visibility['SecurityOptions']);
$tpl['LINK:SessionsOptions']    = sumo_get_action_link('ModifySettings', 'SessionsOptions', $visibility['SessionsOptions']);
$tpl['LINK:AccountsOptions']    = sumo_get_action_link('ModifySettings', 'AccountsOptions', $visibility['AccountsOptions']);
$tpl['LINK:LoggingOptions']     = sumo_get_action_link('ModifySettings', 'LoggingOptions',  $visibility['LoggingOptions']);        
$tpl['LINK:AccessPointOptions'] = sumo_get_action_link('ModifySettings', 'AccessPointOptions', $visibility['AccessPointOptions']);
        
?>