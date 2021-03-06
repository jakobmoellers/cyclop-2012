<?php
/**
 * SUMO MODULE: Network | Add Datasource
 * 
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

// Patch for MySQLUsers
if(strtolower($_POST['host'] == 'localhost')) $_POST['host'] = '127.0.0.1';
	
$data = array(	array('dsname',    $_POST['name'], 1),
		array('type',      $_POST['type'], 1),
		array('hostname',  $_POST['host']),
		array('port',	   $_POST['port']),
		array('username',  $_POST['username']),
		array('password',  $_POST['password']),
		array('db_name',   $_POST['db_name']),
		array('db_table',  $_POST['db_table']),
		array('enctype',   $_POST['enctype']),
		array('username',  $_POST['db_field_user']),
		array('password',  $_POST['db_field_password']),
		array('enctype',   $_POST['enctype']),
		array('ldap_base', $_POST['ldap_base']));
		
$validate = sumo_validate_data_network($data, TRUE);
		
if($validate[0]) 
{	
	// verify if datasource already exist
	if(sumo_verify_datasource_exist('name', $_POST['name'])) $validate = array(FALSE, sumo_get_message('I09002C', $_POST['name']));
	
	$ds = sumo_get_available_datasources(true);
	
	// MySQL / MySQLUsers / Postgres / Oracle / Joomla
	if(in_array($_POST['type'], $ds) && (!$_POST['db_name'] || !$_POST['db_table'] || !$_POST['db_field_user'] || !$_POST['db_field_password']))
	{
		$validate = array(FALSE, sumo_get_message('I09004C', $_POST['db_name']));
	}
	
	// LDAP/LDAPS
	if(($_POST['type'] == 'LDAP' || $_POST['type'] == 'LDAPS' || $_POST['type'] == 'ADAM') && !$_POST['ldap_base'])
	{
		$validate = array(FALSE, sumo_get_message('I09005C'));			
	}				
}


if(!$validate[0]) 
{
	$tpl['MESSAGE:H'] = sumo_get_message('DataSourceNotAdded', $_POST['name']).":<br>".$validate[1];			
}
else 
{										
	$insert = sumo_add_datasource(	array(	'name' 			=> $_POST['name'],
						'type' 	 		=> $_POST['type'],
						'host'  		=> $_POST['host'],
						'port' 	 	        => $_POST['port'],
						'username' 	  	=> $_POST['username'],
						'password'  	    	=> $_POST['password'],
						'db_name'    	    	=> $_POST['db_name'],
						'db_table'         	=> $_POST['db_table'],
						'db_field_user'		=> $_POST['db_field_user'],
						'db_field_password' 	=> $_POST['db_field_password'],
						'enctype' 		=> $_POST['enctype'],
						'ldap_base' 	    	=> $_POST['ldap_base']	)
				      );
	
	if($insert) {
		$tpl['MESSAGE:L'] 	  = sumo_get_message('DataSourceAdded', $_POST['name']);
		$tpl['GET:WindowScripts'] = "sumo_ajax_get('network.content','?module=network&action=dlist&decoration=false');";
	}
	else 
		$tpl['MESSAGE:H'] = sumo_get_message('DataSourceNotAdded', $_POST['name']);
}		
		
require "action.new_datasource.php";

?>