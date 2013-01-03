<?php
/**
 * SUMO MODULE: Settings | Modify
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
 	
// Fix
if(!$_POST['config']['iptocountry']['enabled']) $_POST['config']['iptocountry']['enabled'] = 0;
if(!$_POST['config']['console']['tip']) 	$_POST['config']['console']['tip'] = 0;


$data = array(  array('language', 	        $_POST['config']['server']['language'], 1),
                array('date_format',     	$_POST['config']['server']['date_format'], 1),
                array('time_format',     	$_POST['config']['server']['time_format'], 1),
                array('boolean',		$_POST['config']['iptocountry']['enabled']),
                array('database.optimize_hits', $_POST['config']['database']['optimize_hits'], 1),
                array('admin_name',	        $_POST['config']['server']['admin']['name'], 1), 
                array('email',		        $_POST['config']['server']['admin']['email']),     
        
		array('boolean',		$_POST['config']['console']['tip']),
			
		array('security.banned_time', 	     $_POST['config']['security']['banned_time'], 1), 
		array('security.max_login_attempts', $_POST['config']['security']['max_login_attempts'], 1),
		array('boolean',  		     $_POST['config']['security']['access_violations']),
		  
		array('accounts.life',    	    $_POST['config']['accounts']['life'], 1),
		array('accounts.life', 	  	    $_POST['config']['accounts']['password']['life']),
		array('boolean', 		    $_POST['config']['accounts']['registration']['enabled']),
		array('accounts.registration.life', $_POST['config']['accounts']['registration']['life']),
		array('boolean', 		    $_POST['config']['accounts']['registration']['notify']['reg']), 
		array('boolean', 		    $_POST['config']['accounts']['registration']['notify']['unreg']), 
		array('boolean', 	 	    $_POST['config']['accounts']['notify']['updates']),
		array('boolean', 	 	    $_POST['config']['accounts']['notify']['status']),
		array('boolean', 	 	    $_POST['config']['accounts']['notify']['expired']),
		array('boolean', 		    $_POST['config']['accesspoints']['stats']['enabled']),  
			  
		array('accesspoints.name',  $_POST['name'], 1),
		array('accesspoints.group', $_POST['config']['accesspoints']['def_group'], 1),
		array('accesspoints.theme', $_POST['config']['accesspoints']['def_theme'], 1), 
              
		array('sessions.timeout',    	$_POST['config']['sessions']['timeout'], 1),
		array('boolean',    		$_POST['config']['sessions']['auto_regenerate_id'], 1),
		array('connections.timeout', 	$_POST['config']['connections']['timeout'], 1),
			  
		array('boolean',   	   $_POST['config']['logs']['system']['database']['enabled']), 
		array('logs.life', 	   $_POST['config']['logs']['system']['database']['life']), 
		array('boolean',   	   $_POST['config']['logs']['system']['file']['enabled']), 
		array('logs.life', 	   $_POST['config']['logs']['system']['file']['life']), 
		array('logs.file.size',    $_POST['config']['logs']['system']['file']['size']), 
		array('boolean',   	   $_POST['config']['logs']['system']['email']['enabled']),  
                                       
		array('boolean',   	   $_POST['config']['logs']['errors']['database']['enabled']), 
		array('logs.life', 	   $_POST['config']['logs']['errors']['database']['life']), 
		array('boolean',   	   $_POST['config']['logs']['errors']['file']['enabled']), 
		array('logs.life', 	   $_POST['config']['logs']['errors']['file']['life']), 
		array('logs.file.size',    $_POST['config']['logs']['errors']['file']['size']), 
		array('boolean',   	   $_POST['config']['logs']['errors']['email']['enabled']), 
              
		array('boolean',   	   $_POST['config']['logs']['access']['database']['enabled']), 
		array('logs.life', 	   $_POST['config']['logs']['access']['database']['life']), 
		array('boolean',   	   $_POST['config']['logs']['access']['file']['enabled']), 
		array('logs.life', 	   $_POST['config']['logs']['access']['file']['life']), 
		array('logs.file.size',    $_POST['config']['logs']['access']['file']['size']), 
		array('boolean',   	   $_POST['config']['logs']['access']['email']['enabled'])
        ); 

$validate = sumo_validate_data_settings($data, TRUE);			         						
			
if(!$validate[0]) 
{
	$tpl['MESSAGE:H'] = $language['SettingsNotUpdated'].": ".$validate[1];
}
else 
{									
	// AP def_names fix
	$languages = sumo_get_available_languages();
	$names = "";
		
	for($l=0; $l<count($languages); $l++)
	{
		$names[$l] = $languages[$l].":".$_POST['name'][$languages[$l]];
	}
		
	$_POST['config']['accesspoints']['def_name'] = implode(";", $names);
	$_POST['config']['server']['locale'] 	     = sumo_get_locale($_POST['config']['server']['language']);
	
	$update = sumo_update_config('server', $_POST['config']);
              
	if($update)
		$tpl['MESSAGE:L'] = $language['SettingsUpdated'];
	else 
		$tpl['MESSAGE:H'] = $language['SettingsNotUpdated'];
}				

require "action.edit.php";

?>